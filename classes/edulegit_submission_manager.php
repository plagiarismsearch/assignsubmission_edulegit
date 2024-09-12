<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The assignsubmission_edulegit submission manager class.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

class edulegit_submission_manager {

    protected edulegit_config $config;
    protected edulegit_client $client;
    protected edulegit_submission_repository $repository;

    public function __construct(edulegit_config $config, edulegit_client $client,
            edulegit_submission_repository $repository) {
        $this->config = $config;
        $this->client = $client;
        $this->repository = $repository;
    }

    public function init($submission, $options = []): ?edulegit_submission_entity {
        if (empty($submission) || empty($submission->assignment) || empty($submission->userid)) {
            return null;
        }
        $edulegitsubmission = $this->get_or_create_edulegit_submission($submission);
        if (!$edulegitsubmission) {
            return null;
        }

        $assignment = $this->repository->get_assignment_info($submission->assignment);

        $callbackurl = new \moodle_url('/mod/assign/submission/edulegit/callback.php');

        $autoplagiarismcheck = (bool) $this->config->get_plugin_or_global_config('enable_plagiarism');
        $autoaicheck = (bool) $this->config->get_plugin_or_global_config('enable_ai');
        $mustrecordevents = (bool) $this->config->get_plugin_or_global_config('enable_plagiarism');
        $mustrecordscreen = (bool) $this->config->get_plugin_or_global_config('enable_screen');
        $mustrecordcamera = (bool) $this->config->get_plugin_or_global_config('enable_camera');
        $mustrecognizeattentionmap = (bool) $this->config->get_plugin_or_global_config('enable_attention');

        $data = [
                'meta' => [
                        'callbackUrl' => $callbackurl,
                        'moodle' => $this->config->get_release(),
                        'plugin' => $this->config->get_plugin_release(),
                ],
                'user' => [
                        'externalId' => (int) $submission->userid,
                        'email' => $options['user']?->email ?? null,
                        'firstName' => $options['user']?->firstname ?? null,
                        'lastName' => $options['user']?->lastname ?? null,
                ],
                'taskUser' => [
                        'externalId' => (int) $edulegitsubmission->id,
                ],
                'task' => [
                        'externalId' => (int) $assignment->id,
                        'title' => $assignment->name ?: $assignment->id,
                        'text' => $assignment->activity ?: ($assignment->intro ?? ''),
                        'description' => ($assignment->intro ?? ''),
                        'startedAt' => $assignment->allowsubmissionsfromdate ?? null,
                        'finishedAt' => $assignment->duedate ?? ($assignment->gradingduedate ?? null),
                ],
                'course' => [
                        'externalId' => (int) $assignment->course,
                        'title' => $assignment->course_fullname ?: $assignment->course_shortname,
                        'text' => $assignment->course_summary ?? '',
                        'startedAt' => $assignment->course_startdate ?? null,
                        'finishedAt' => $assignment->course_enddate ?? null,
                        'setting' => [
                                'autoPlagiarismCheck' => $autoplagiarismcheck,
                                'autoAiCheck' => $autoaicheck,
                                'mustRecordEvents' => $mustrecordevents,
                                'mustRecordScreen' => $mustrecordscreen,
                                'mustRecordCamera' => $mustrecordcamera,
                                'mustRecognizeAttentionMap' => $mustrecognizeattentionmap,
                        ]
                ]
        ];

        $response = $this->client->init_assignment($data);

        $payload = $response->get_payload();
        $responseobject = $payload->data ?? null;

        // Same API service errors.
        if (!$response->get_success() || empty($payload->success) || !$responseobject) {
            $error = $payload->error ?? ($response->get_error() ?: 'Edulegit service error.');

            $edulegitsubmission->status = 0;
            $edulegitsubmission->error = $error;

            return $this->repository->update_submission($edulegitsubmission) ? $edulegitsubmission : null;
        }

        return $this->sync_submission_edulegit_response($submission, $responseobject);
    }

    public function sync(int $submissionid): ?edulegit_submission_entity {
        $edulegitsubmission = $this->repository->get_submission($submissionid);

        return $edulegitsubmission;
    }

    private function get_or_create_edulegit_submission($submission): ?edulegit_submission_entity {
        $edulegitsubmission = $this->repository->get_submission($submission->id);
        if ($edulegitsubmission) {
            return $edulegitsubmission;
        }

        $edulegitsubmission = new edulegit_submission_entity();
        $edulegitsubmission->submission = $submission->id;
        $edulegitsubmission->assignment = $submission->assignment;

        $edulegitsubmission->id = $this->repository->insert_submission($edulegitsubmission);

        if (!$edulegitsubmission->id) {
            return null;
        }

        return $edulegitsubmission;
    }

    private function sync_submission_edulegit_response($submission, $data): ?edulegit_submission_entity {
        $edulegitsubmission = $this->get_or_create_edulegit_submission($submission);
        if (!$edulegitsubmission) {
            return null;
        }

        $edulegitsubmission->title = $data->taskDocument->title ?? null;
        $edulegitsubmission->content = $data->taskDocument->content ?? null;
        $edulegitsubmission->document_id = $data->taskDocument->id ?? 0;
        $edulegitsubmission->task_id = $data->task->id ?? ($data->taskUser->taskId ?? 0);
        $edulegitsubmission->task_user_id = $data->taskUser->id ?? ($data->taskUser->taskUserId);
        $edulegitsubmission->url = $data->sharedDocument->viewUrl ?? ($data->sharedDocument->pdfUrl ?? null);
        $edulegitsubmission->auth_key = $data->sharedDocument->authKey ?? null;
        $edulegitsubmission->score = $data->taskDocument->score ?? null;
        $edulegitsubmission->plagiarism = $data->taskDocument->plagiarism ?? null;
        $edulegitsubmission->ai_rate = $data->taskDocument->aiAverageProbability ?? null;
        $edulegitsubmission->ai_probability = $data->taskDocument->aiProbability ?? null;
        $edulegitsubmission->base_url = $data->baseUrl ?? null;
        $edulegitsubmission->user_id = $data->user->id ?? null;
        $edulegitsubmission->user_key = $data->user->loginTimeToken ?? null;

        $edulegitsubmission->error = null;
        $edulegitsubmission->status = 1;

        return $this->repository->update_submission($edulegitsubmission) ? $edulegitsubmission : null;
    }

}

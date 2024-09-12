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
 * The assignsubmission_edulegit core class.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

class edulegit_callback {

    protected edulegit_submission_repository $repository;

    public function __construct(?edulegit_submission_repository $repository = null) {
        $this->repository = $repository ?? new edulegit_submission_repository();
    }

    public function handle(object $payload) {
        $event = $payload->event ?? null;
        $data = $payload->data ?? [];

        if ($event == 'taskUser.sync') {
            return $this->sync_task_user($data);
        }
        return null;
    }

    private function sync_task_user($data) {
        $id = $data->externalId ?? null;
        if (!$id) {
            return null;
        }

        $submission = $this->repository->get_by_id($id);

        if (!$submission) {
            return null;
        }

        $submission->status = 1;
        $submission->error = '';

        if (isset($data->title)) {
            $submission->title = $data->title;
        }
        if (isset($data->content)) {
            $submission->content = $data->content;
        }
        if (isset($data->url)) {
            $submission->url = $data->url;
        }
        if (isset($data->authKey)) {
            $submission->auth_key = $data->authKey;
        }
        if (isset($data->score)) {
            $submission->score = $data->score;
        }
        if (isset($data->plagiarism)) {
            $submission->plagiarism = $data->plagiarism;
        }
        if (isset($data->aiAverageProbability)) {
            $submission->ai_rate = $data->aiAverageProbability;
        }
        if (isset($data->aiProbability)) {
            $submission->ai_probability = $data->aiProbability;
        }
        if (isset($data->loginTimeToken)) {
            $submission->user_key = $data->loginTimeToken;
        }

        return $this->repository->update_submission($submission) ? $submission->id : null;
    }

}

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
 * The assignsubmission_edulegit submission entity class.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

/**
 * Class edulegit_submission_entity
 *
 * This class represents the submission entity for the EduLegit plugin.
 */
class edulegit_submission_entity {

    /**
     * @var int|null The unique identifier for the submission entity.
     */
    public ?int $id = null;

    /**
     * @var int The ID of the associated assignment.
     */
    public int $assignment = 0;

    /**
     * @var int The ID of the submission.
     */
    public int $submission = 0;

    /**
     * @var string|null The title of the submission.
     */
    public ?string $title = null;

    /**
     * @var string|null The content of the submission.
     */
    public ?string $content = null;

    /**
     * @var int|null The ID of the document stored in the EduLegit system.
     */
    public ?int $documentid = null;

    /**
     * @var int|null The task ID related to this submission.
     */
    public ?int $taskid = null;

    /**
     * @var int|null The task user ID associated with the submission.
     */
    public ?int $taskuserid = null;

    /**
     * @var int|null The user ID of the submitter.
     */
    public ?int $userid = null;

    /**
     * @var string|null The user key for login access.
     */
    public ?string $userkey = null;

    /**
     * @var string|null The base URL to access the document.
     */
    public ?string $baseurl = null;

    /**
     * @var string|null The URL of the submission.
     */
    public ?string $url = null;

    /**
     * @var string|null The authentication key for the submission.
     */
    public ?string $authkey = null;

    /**
     * @var float|null The score given to the submission.
     */
    public ?float $score = null;

    /**
     * @var float|null The plagiarism score of the submission.
     */
    public ?float $plagiarism = null;

    /**
     * @var float|null The AI rate for the submission.
     */
    public ?float $airate = null;

    /**
     * @var float|null The probability that AI tools were used.
     */
    public ?float $aiprobability = null;

    /**
     * @var int The current status of the submission.
     */
    public int $status = 0;

    /**
     * @var string|null Any error messages associated with the submission.
     */
    public ?string $error = null;

    /**
     * @var int|null The timestamp when the submission was created.
     */
    public ?int $createdat = null;

    /**
     * @var int|null The timestamp when the submission was last updated.
     */
    public ?int $updatedat = null;

    /**
     * Constructor for the submission entity.
     *
     * @param array|object $values An array or object of key-value pairs to initialize the entity.
     */
    public function __construct(array|object $values = []) {
        foreach ($values as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Checks if the submission content is empty.
     *
     * @return bool True if the content is empty, false otherwise.
     */
    public function is_empty(): bool {
        return empty($this->content);
    }

    /**
     * Retrieves the base URL for the document.
     *
     * @return string The base URL for the document.
     */
    public function get_baseurl(): string {
        return $this->baseurl ?: 'https://app.edulegit.com/document/' . $this->documentid;
    }

    /**
     * Retrieves the URL to view the submission.
     *
     * @return string The URL to view the submission.
     */
    public function get_view_url(): string {
        return $this->url ?? '';
    }

    /**
     * Retrieves the URL for user login with a token.
     *
     * @return string The URL for user login with the user key.
     */
    public function get_user_login_url(): string {
        if (empty($this->userkey)) {
            return '';
        }

        return $this->get_baseurl() . '?tt=' . $this->userkey;
    }

    /**
     * Retrieves the URL to access the PDF version of the document.
     *
     * @return string The PDF access URL with the authentication key.
     */
    public function get_pdf_url(): string {
        return $this->get_baseurl() . '/pdf?key=' . $this->authkey;
    }

    /**
     * Retrieves the URL to access the HTML version of the document.
     *
     * @return string The HTML access URL with the authentication key.
     */
    public function get_html_url(): string {
        return $this->get_baseurl() . '/html?key=' . $this->authkey;
    }

    /**
     * Retrieves the URL to access the TXT version of the document.
     *
     * @return string The TXT access URL with the authentication key.
     */
    public function get_txt_url(): string {
        return $this->get_baseurl() . '/txt?key=' . $this->authkey;
    }

    /**
     * Retrieves the URL to access the DOCX version of the document.
     *
     * @return string The DOCX access URL with the authentication key.
     */
    public function get_docx_url(): string {
        return $this->get_baseurl() . '/docx?key=' . $this->authkey;
    }

}

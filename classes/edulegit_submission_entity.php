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

class edulegit_submission_entity {

    public ?int $id = null;
    public int $assignment = 0;
    public int $submission = 0;
    public ?string $title = null;
    public ?string $content = null;
    public ?int $document_id = null;
    public ?int $task_id = null;
    public ?int $task_user_id = null;
    public ?int $user_id = null;
    public ?string $user_key = null;
    public ?string $base_url = null;
    public ?string $url = null;
    public ?string $auth_key = null;
    public ?float $score = null;
    public ?float $plagiarism = null;
    public ?float $ai_rate = null;
    public ?float $ai_probability = null;
    public int $status = 0;
    public ?string $error = null;
    public ?int $created_at = null;
    public ?int $updated_at = null;

    public function __construct(array|object $values = []) {
        foreach ($values as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function is_empty(): bool {
        return empty($this->content);
    }

    public function get_base_url(): string {
        return $this->base_url ?: 'https://app.edulegit.com/document/' . $this->document_id;
    }

    public function get_view_url(): string {
        return $this->url ?? '';
    }

    public function get_user_login_url(): string {
        if (empty($this->user_key)) {
            return '';
        }

        return $this->get_base_url() . '?tt=' . $this->user_key;
    }

    public function get_pdf_url(): string {
        return $this->get_base_url() . '/pdf?key=' . $this->auth_key;
    }

    public function get_html_url(): string {
        return $this->get_base_url() . '/html?key=' . $this->auth_key;
    }

    public function get_txt_url(): string {
        return $this->get_base_url() . '/txt?key=' . $this->auth_key;
    }

    public function get_docx_url(): string {
        return $this->get_base_url() . '/docx?key=' . $this->auth_key;
    }

}

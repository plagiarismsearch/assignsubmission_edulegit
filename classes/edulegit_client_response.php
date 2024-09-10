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
 * The assignsubmission_edulegit API client response class.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

class edulegit_client_response {

    protected ?bool $success = null;
    protected ?int $code = null;
    protected ?string $url = null;
    protected string $error = '';
    protected array $info = [];
    protected string $body = '';
    protected mixed $payload = null;

    public function __construct(string $body, array $info, string $error, ?string $url = null) {
        $this->body = $body;
        $this->info = $info;
        $this->error = $error;
        $this->url = $url;
    }

    public function get_success(): bool {
        if ($this->success === null) {
            $this->success = ($this->get_code() >= 200 && $this->get_code() < 300 && !$this->error);
        }
        return $this->success;
    }

    public function set_success($success): void {
        $this->success = (bool) $success;
    }

    public function get_code(): int {
        if ($this->code === null) {
            $this->code = (int) $this->info['http_code'] ?? 0;
        }
        return $this->code;
    }

    public function set_code(?int $code): void {
        $this->code = $code;
    }

    public function get_url(): ?string {
        return $this->url;
    }

    public function set_url(?string $url): void {
        $this->url = $url;
    }

    public function get_error(): ?string {
        return $this->error;
    }

    public function set_error(?string $error): void {
        $this->error = $error;
    }

    public function get_info(): array {
        return $this->info ?? [];
    }

    public function set_info(array $info): void {
        $this->info = $info;
    }

    public function get_body(): ?string {
        return $this->body;
    }

    public function set_body(?string $body): void {
        $this->body = $body;
    }

    public function get_payload(): mixed {
        if ($this->payload === null) {
            $this->payload = edulegit_helper::json_decode($this->get_body());
        }
        return $this->payload;
    }

}
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
 * This class encapsulates the response from an API request made using the edulegit_client.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

/**
 * Class edulegit_client_response
 *
 * This class manages and processes the response from an API call made by the edulegit_client.
 */
class edulegit_client_response {

    /**
     * Indicates whether the API request was successful.
     *
     * @var bool|null
     */
    protected ?bool $success = null;

    /**
     * The HTTP response code.
     *
     * @var int|null
     */
    protected ?int $code = null;

    /**
     * The URL that was accessed during the API request.
     *
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * Error message, if any, from the API request.
     *
     * @var string
     */
    protected string $error = '';

    /**
     * Information about the cURL response.
     *
     * @var array
     */
    protected array $info = [];

    /**
     * The raw body of the API response.
     *
     * @var string
     */
    protected string $body = '';

    /**
     * Decoded response payload.
     *
     * @var mixed
     */
    protected mixed $payload = null;

    /**
     * Edulegit_client_response constructor.
     *
     * @param string $body The raw body of the API response.
     * @param array $info Array of cURL response information.
     * @param string $error The error message, if any.
     * @param string|null $url The URL used in the API request.
     */
    public function __construct(string $body, array $info, string $error, ?string $url = null) {
        $this->body = $body;
        $this->info = $info;
        $this->error = $error;
        $this->url = $url;
    }

    /**
     * Retrieves the success status of the API request.
     *
     * Determines success based on the HTTP response code and presence of errors.
     *
     * @return bool True if the request was successful, false otherwise.
     */
    public function get_success(): bool {
        if ($this->success === null) {
            $this->success = ($this->get_code() >= 200 && $this->get_code() < 300 && !$this->error);
        }
        return $this->success;
    }

    /**
     * Sets the success status of the API request.
     *
     * @param bool $success The success status to set.
     * @return void
     */
    public function set_success($success): void {
        $this->success = (bool) $success;
    }

    /**
     * Retrieves the HTTP response code from the API request.
     *
     * @return int The HTTP response code.
     */
    public function get_code(): int {
        if ($this->code === null) {
            $this->code = (int) $this->info['http_code'] ?? 0;
        }
        return $this->code;
    }

    /**
     * Sets the HTTP response code for the API request.
     *
     * @param int|null $code The HTTP response code to set.
     * @return void
     */
    public function set_code(?int $code): void {
        $this->code = $code;
    }

    /**
     * Retrieves the URL used in the API request.
     *
     * @return string|null The URL of the API request.
     */
    public function get_url(): ?string {
        return $this->url;
    }

    /**
     * Sets the URL used in the API request.
     *
     * @param string|null $url The URL to set.
     * @return void
     */
    public function set_url(?string $url): void {
        $this->url = $url;
    }

    /**
     * Retrieves the error message from the API request.
     *
     * @return string|null The error message, or null if none exists.
     */
    public function get_error(): ?string {
        return $this->error;
    }

    /**
     * Sets the error message for the API request.
     *
     * @param string|null $error The error message to set.
     * @return void
     */
    public function set_error(?string $error): void {
        $this->error = $error;
    }

    /**
     * Retrieves the cURL response information.
     *
     * @return array An array of cURL response information.
     */
    public function get_info(): array {
        return $this->info ?? [];
    }

    /**
     * Sets the cURL response information.
     *
     * @param array $info The cURL response information to set.
     * @return void
     */
    public function set_info(array $info): void {
        $this->info = $info;
    }

    /**
     * Retrieves the raw body of the API response.
     *
     * @return string|null The raw body of the response.
     */
    public function get_body(): ?string {
        return $this->body;
    }

    /**
     * Sets the raw body of the API response.
     *
     * @param string|null $body The raw body to set.
     * @return void
     */
    public function set_body(?string $body): void {
        $this->body = $body;
    }

    /**
     * Retrieves the decoded payload from the API response body.
     *
     * @return mixed The decoded payload.
     */
    public function get_payload(): mixed {
        if ($this->payload === null) {
            $this->payload = edulegit_helper::json_decode($this->get_body());
        }
        return $this->payload;
    }
}

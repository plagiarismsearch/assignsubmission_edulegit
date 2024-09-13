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
 * The assignsubmission_edulegit API client class.
 *
 * Handles communication with the EduLegit API for Moodle assignments.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

/**
 * Class edulegit_client
 *
 * This class is responsible for sending requests to the EduLegit API and managing the API connection.
 */
class edulegit_client {

    /**
     * API authentication key.
     *
     * @var string
     */
    private string $authkey = '';

    /**
     * Base URL for the EduLegit API.
     *
     * @var string
     */
    private string $baseurl = 'https://api.edulegit.com';

    /**
     * Enables or disables debugging.
     *
     * @var bool
     */
    private bool $debug = true;

    /**
     * Constructor for the edulegit_client class.
     *
     * @param string $authkey The authentication key for API access.
     */
    public function __construct(string $authkey) {
        $this->authkey = $authkey;
    }

    /**
     * Sends a request to the EduLegit API.
     *
     * @param string $method The HTTP method to use (e.g., 'POST', 'GET').
     * @param string $uri The URI of the API endpoint.
     * @param array $data The data to be sent with the request.
     * @return edulegit_client_response The API response.
     */
    public function fetch(string $method, string $uri, array $data = []): edulegit_client_response {
        $url = $this->baseurl . $uri;

        $curl = curl_init($this->filter_url($url));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        $postfields = $this->build_post_fields($data);
        if ($postfields) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
        }
        $headers = [
                'X-API-TOKEN' => $this->authkey,
                'Content-Type' => 'application/json',
                'User-Agent' => 'Mozilla/5.0 Edulegit plugin/1.0',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->build_headers($headers));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 7);

        if ($this->debug) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $body = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return new edulegit_client_response((string) $body, (array) $info, (string) $error, $url);
    }

    /**
     * Filters and encodes a URL.
     *
     * @param string $url The URL to be filtered.
     * @return string The filtered URL.
     */
    private function filter_url(string $url) {
        return str_replace(
                ['%3A', '%2F', '%3F', '%3D', '%26', '%40', '%25', '%23'],
                [':', '/', '?', '=', '&', '@', '%', '#'],
                rawurlencode($url)
        );
    }

    /**
     * Builds the post fields for the cURL request.
     *
     * @param array $data The data to be encoded and sent.
     * @return string The JSON-encoded data.
     */
    private function build_post_fields(array $data) {
        return edulegit_helper::json_encode($data);
    }

    /**
     * Constructs the HTTP headers for the request.
     *
     * @param array $headers The array of headers (key-value pairs).
     * @return array The formatted headers.
     */
    protected function build_headers(array $headers) {
        $result = [];
        foreach ($headers as $key => $value) {
            $result[] = $key . ': ' . $value;
        }
        return $result;
    }

    /**
     * Initializes a Moodle assignment via the API.
     *
     * @param array $data The data to initialize the assignment.
     * @return edulegit_client_response The API response.
     */
    public function init_assignment($data): edulegit_client_response {
        return $this->fetch('POST', '/init-moodle-assignment', $data);
    }
}

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
 * Payload validator for the assignsubmission_edulegit plugin.
 *
 * @package   assignsubmission_edulegit
 * @subpackage validation
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

/**
 * Class edulegit_payload_validator
 *
 * This class validates and verifies the signature of payloads sent to the plugin.
 */
class edulegit_payload_validator {

    /**
     * The authorization key used for signature generation.
     *
     * @var string
     */
    private string $authkey = '';

    /**
     * Constructor for the payload validator.
     *
     * @param string $authkey The authorization key used to sign payloads.
     */
    public function __construct(string $authkey) {
        $this->authkey = $authkey;
    }

    /**
     * Validates the payload structure to ensure required fields are present.
     *
     * @param mixed $payload The payload to be validated.
     * @return bool True if the payload is valid, false otherwise.
     */
    public function is_valid(mixed $payload): bool {
        if (!is_object($payload)) {
            return false;
        }

        foreach (['event', 'data', 'timestamp', 'signature'] as $key) {
            if (!property_exists($payload, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates the payload signature to ensure the data integrity.
     *
     * @param object $payload The payload object containing the signature.
     * @return bool True if the signature matches, false otherwise.
     */
    public function is_signed(object $payload): bool {
        if (!$this->is_valid($payload)) {
            return false;
        }

        $signature = $this->generate_signature($payload->event . $payload->timestamp);

        return $signature && $signature === $payload->signature;
    }

    /**
     * Generates a signature using the auth key and data provided.
     *
     * @param string $data The data used to generate the signature.
     * @return string|null The generated signature, or null if authkey or data is missing.
     */
    private function generate_signature(string $data): ?string {
        if (!$this->authkey || !$data) {
            return null;
        }
        return md5(mb_substr($this->authkey, 0, 10) . $data);
    }

}

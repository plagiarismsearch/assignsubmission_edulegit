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

class edulegit_payload_validator {

    private string $auth_key = '';

    public function __construct(string $auth_key) {
        $this->auth_key = $auth_key;
    }

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

    public function is_signed(object $payload): bool {
        if (!$this->is_valid($payload)) {
            return false;
        }

        $signature = $this->generate_signature($payload->event . $payload->timestamp);

        return $signature && $signature === $payload->signature;

    }

    private function generate_signature(string $data): ?string {
        if (!$this->auth_key || !$data) {
            return null;
        }
        return md5(mb_substr($this->auth_key, 0, 10) . $data);
    }

}
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
 * The helper class for the assignsubmission_edulegit plugin.
 *
 * Provides utility methods for JSON encoding and decoding.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

/**
 * Class edulegit_helper
 *
 * This class provides helper methods such as JSON encoding and decoding, which are used throughout the plugin.
 */
class edulegit_helper {

    /**
     * Decode a JSON string into a PHP variable.
     *
     * @param string $json The JSON string to decode.
     * @return mixed The decoded value, typically an object or an array.
     */
    public static function json_decode(string $json): mixed {
        return json_decode($json, false, 512, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * Encode a PHP variable into a JSON string.
     *
     * @param mixed $data The data to encode as JSON.
     * @return string|false The JSON-encoded string, or false on failure.
     */
    public static function json_encode(mixed $data): string|false {
        return json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE);
    }

}

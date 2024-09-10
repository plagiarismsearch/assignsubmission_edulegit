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
 * The assignsubmission_edulegit config class.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

class edulegit_config {

    private ?\stdClass $config = null;

    public function __construct(\stdClass $config) {
        $this->config = $config;

    }

    public function get($name, $default = null) {
        $value = $this->config->{$name} ?? false;
        return $value !== false ? $value : $default;
    }

    public function get_global($name, $default = null) {
        $value = get_config('assignsubmission_edulegit', $name);
        return $value !== false ? $value : $default;
    }

    public function get_plugin_or_global_config($name, $default = null) {
        return $this->get($name, $this->get_global($name, $default));
    }

    public function get_release() {
        global $CFG;
        if (isset($CFG->version)) {
            return $CFG->version;
        }
        if (isset($CFG->release)) {
            return $CFG->release;
        }
        return null;
    }

    public function get_plugin_release() {
        return $this->get_global('version');
    }

}
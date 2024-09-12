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

class edulegit_core {

    private \assignsubmission_edulegit\edulegit_config $config;
    private \assignsubmission_edulegit\edulegit_submission_repository $repository;
    private \assignsubmission_edulegit\edulegit_submission_manager $manager;

    public function __construct(\stdClass $config) {
        $this->config = new \assignsubmission_edulegit\edulegit_config($config);
        $this->repository = new \assignsubmission_edulegit\edulegit_submission_repository();
        $client = new edulegit_client($this->config->get_global('api_token'));
        $this->manager =
                new \assignsubmission_edulegit\edulegit_submission_manager($this->config, $client, $this->repository);
    }

    public function get_config(): edulegit_config {
        return $this->config;
    }

    public function get_repository(): edulegit_submission_repository {
        return $this->repository;
    }

    public function get_manager(): edulegit_submission_manager {
        return $this->manager;
    }

}

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
 * The assignsubmission_edulegit menu builder class.
 *
 * @package   assignsubmission_edulegit
 * @author    Alex Crosby <developer@edulegit.com>
 * @copyright @2024 EduLegit.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_edulegit;

class edulegit_submission_menu_builder {

    public function build(edulegit_submission_entity $edulegitsubmission): \action_menu {
        $emptyicon = new \pix_icon('', '');

        $menu = new \action_menu();

        $title = $edulegitsubmission->title ?: $this->translate('submission');
        $menu->actionicon = $emptyicon;
        $menu->actiontext = shorten_text($title, 32);
        $menu->set_action_label($title);

        $viewurl = $edulegitsubmission->get_view_url();
        if ($viewurl) {
            $menu->add(
                    new \action_menu_link(
                            new \moodle_url($viewurl),
                            $emptyicon,
                            $this->translate('as_view'),
                            false
                    ));
        }

        $pdfurl = $edulegitsubmission->get_pdf_url();
        if ($pdfurl) {
            $menu->add(
                    new \action_menu_link(
                            new \moodle_url($pdfurl),
                            $emptyicon,
                            $this->translate('as_pdf'),
                            false
                    ));
        }
        $docxurl = $edulegitsubmission->get_docx_url();
        if ($docxurl) {
            $menu->add(
                    new \action_menu_link(
                            new \moodle_url($docxurl),
                            $emptyicon,
                            $this->translate('as_docx'),
                            false
                    ));
        }
        $htmlurl = $edulegitsubmission->get_html_url();
        if ($htmlurl) {
            $menu->add(
                    new \action_menu_link(
                            new \moodle_url($htmlurl),
                            $emptyicon,
                            $this->translate('as_html'),
                            false
                    ));
        }
        $txturl = $edulegitsubmission->get_txt_url();
        if ($txturl) {
            $menu->add(
                    new \action_menu_link(
                            new \moodle_url($txturl),
                            $emptyicon,
                            $this->translate('as_txt'),
                            false
                    ));
        }

        return $menu;
    }

    /**
     * Returns a localized string.
     *
     * @param string $identifier The key identifier for the localized string
     * @return \lang_string|string
     */
    private function translate(string $identifier) {
        return get_string($identifier, 'assignsubmission_edulegit');
    }

}
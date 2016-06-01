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
 * @package    enrol_apply
 * @copyright  emeneo.com (http://emeneo.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     emeneo.com (http://emeneo.com/)
 * @author     Johannes Burk <johannes.burk@sudile.com>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/editlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

class enrol_apply_apply_form extends moodleform {
    protected $instance;

    /**
     * Overriding this function to get unique form id for multiple apply enrolments
     *
     * @return string form identifier
     */
    protected function get_form_identifier() {
        $formid = $this->_customdata->id.'_'.get_class($this);
        return $formid;
    }

    public function definition() {
        global $USER;

        $mform = $this->_form;
        $instance = $this->_customdata;
        $this->instance = $instance;
        $plugin = enrol_get_plugin('apply');

        $heading = $plugin->get_instance_name($instance);
        $mform->addElement('header', 'selfheader', $heading);

        $mform->addElement('html', '<p>'.$instance->customtext1.'</p>');
        $mform->addElement('textarea', 'applydescription', get_string('comment', 'enrol_apply'),'cols="80"');
        $mform->setType('applydescription', PARAM_TEXT);

        //user profile
        $editoroptions = $filemanageroptions = null;

        if($instance->customint1){
            useredit_shared_definition($mform, $editoroptions, $filemanageroptions, $USER);
        }

        if($instance->customint2){
            profile_definition($mform, $USER->id);
        }

        $mform->setDefaults((array)$USER);
        
        $this->add_action_buttons(false, get_string('enrolme', 'enrol_self'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $instance->courseid);

        $mform->addElement('hidden', 'instance');
        $mform->setType('instance', PARAM_INT);
        $mform->setDefault('instance', $instance->id);
    }
}

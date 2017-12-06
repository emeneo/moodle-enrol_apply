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
 * Defines the backup_enrol_lti_plugin class.
 *
 * @package   enrol_lti
 * @copyright 2016 Mark Nelson <markn@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Provides the information to backup test enrol instances
 */
class backup_enrol_apply_plugin extends backup_enrol_plugin {

    protected function define_enrol_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '../../enrol', $this->pluginname);

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        $applymaps = new backup_nested_element('applymaps');

        // Now create the enrol own structures
        $applymap = new backup_nested_element('applymap', array('id'), array(
            'enrol', 'status', 'courseid', 'sortorder', 'name', 'enrolperiod', 'enrolstartdate', 'enrolenddate', 'expirynotify', 'expirythreshold', 'notifyall', 'password', 'cost', 'currency', 'roleid', 'customint1', 'customint2', 'customint3', 'customint4', 'customint5', 'customint6', 'customint7', 'customint8', 'customchar1', 'customchar2', 'customchar3', 'customdec1', 'customdec2', 'customtext1', 'customtext2', 'customtext3', 'customtext4', 'timecreated', 'timemodified'));

        // Now the own termmap tree
        $pluginwrapper->add_child($applymaps);
        $applymaps->add_child($applymap);

        // set source to populate the data
        $applymap->set_source_table('enrol_apply_applicationinfo',
                array('enrol'  => backup::VAR_PARENTID));

        return $plugin;
    }
}

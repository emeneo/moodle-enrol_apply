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
 * @copyright  emeneo.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Johannes Burk <johannes.burk@sudile.com>
 */

defined('MOODLE_INTERNAL') || die;

function xmldb_enrol_apply_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016012801) {

        // Define table enrol_apply_applicationinfo to be created.
        $table = new xmldb_table('enrol_apply_applicationinfo');

        // Adding fields to table enrol_apply_applicationinfo.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userenrolmentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('comment', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table enrol_apply_applicationinfo.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('userenrolment', XMLDB_KEY_FOREIGN_UNIQUE, array('userenrolmentid'), 'user_enrolments', array('id'));

        // Conditionally launch create table for enrol_apply_applicationinfo.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Apply savepoint reached.
        upgrade_plugin_savepoint(true, 2016012801, 'enrol', 'apply');
    }

    return true;

}
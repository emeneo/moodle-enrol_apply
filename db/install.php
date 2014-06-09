<?php

/**
 * *************************************************************************
 * *                  Apply	Enrol   				                      **
 * *************************************************************************
 * @copyright   emeneo.com                                                **
 * @link        emeneo.com                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************
*/

/**
 * apply enrol plugin installation script
 *
 * @package    enrol
 * @subpackage apply
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

function xmldb_enrol_apply_install() {
    global $CFG, $DB;

    // migrate welcome message
    if (isset($CFG->sendcoursewelcomemessage)) {
        set_config('sendcoursewelcomemessage', $CFG->sendcoursewelcomemessage, 'enrol_apply'); // new course default
        $DB->set_field('enrol', 'customint4', $CFG->sendcoursewelcomemessage, array('enrol'=>'apply')); // each instance has different setting now
        unset_config('sendcoursewelcomemessage');
    }

    // migrate long-time-no-see feature settings
    if (isset($CFG->longtimenosee)) {
        $nosee = $CFG->longtimenosee * 3600 * 24;
        set_config('longtimenosee', $nosee, 'enrol_apply');
        $DB->set_field('enrol', 'customint2', $nosee, array('enrol'=>'apply'));
        unset_config('longtimenosee');
    }
}

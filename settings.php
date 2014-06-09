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
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_apply_enrolname','',''));
    
    $settings->add(new admin_setting_configtext('enrol_apply/confirmmailsubject','',get_string('confirmmailsubject', 'enrol_apply'),null,PARAM_TEXT,60));
    $settings->add(new admin_setting_heading('enrol_apply_settings', '', get_string('confirmmailcontent_desc', 'enrol_apply')));
    $settings->add(new admin_setting_confightmleditor('enrol_apply/confirmmailcontent', get_string('confirmmailcontent', 'enrol_apply'),'utf-8',''));
    
    $settings->add(new admin_setting_configtext('enrol_apply/cancelmailsubject','',get_string('cancelmailsubject', 'enrol_apply'),null,PARAM_TEXT,60));
    //$settings->add(new admin_setting_heading('enrol_apply_settings', '', get_string('cancelmailcontent_desc', 'enrol_apply')));
    $settings->add(new admin_setting_confightmleditor('enrol_apply/cancelmailcontent', get_string('cancelmailcontent', 'enrol_apply'),'utf-8',''));
    
	$settings->add(new admin_setting_configcheckbox('enrol_apply/sendmailtoteacher',
        get_string('sendmailtoteacher', 'enrol_apply'), '', 0));
    
}

if ($hassiteconfig) { // needs this condition or there is error on login page
    $ADMIN->add('courses', new admin_externalpage('enrol_apply',
            get_string('applymanage', 'enrol_apply'),
            new moodle_url('/enrol/apply/manage.php')));
}

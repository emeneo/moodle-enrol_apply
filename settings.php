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
    $settings->add(new admin_setting_heading('enrol_apply_enrolname','',get_string('pluginname_desc', 'enrol_apply')));
    
    $settings->add(new admin_setting_configtext('enrol_apply/confirmmailsubject','',get_string('confirmmailsubject', 'enrol_apply'),null,PARAM_TEXT,60));

    $settings->add(new admin_setting_heading('enrol_apply_confirmmailcontent', '', get_string('confirmmailcontent_desc', 'enrol_apply')));
    $settings->add(new admin_setting_confightmleditor('enrol_apply/confirmmailcontent', get_string('confirmmailcontent', 'enrol_apply'),'utf-8',''));
    
    $settings->add(new admin_setting_configtext('enrol_apply/cancelmailsubject','',get_string('cancelmailsubject', 'enrol_apply'),null,PARAM_TEXT,60));

    $settings->add(new admin_setting_heading('enrol_apply_cancelmailcontent', '', get_string('cancelmailcontent_desc', 'enrol_apply')));
    $settings->add(new admin_setting_confightmleditor('enrol_apply/cancelmailcontent', get_string('cancelmailcontent', 'enrol_apply'),'utf-8',''));
    
	$settings->add(new admin_setting_configcheckbox('enrol_apply/sendmailtoteacher', get_string('sendmailtoteacher', 'enrol_apply'), '', 0));
    $settings->add(new admin_setting_configcheckbox('enrol_apply/sendmailtomanager', get_string('sendmailtomanager', 'enrol_apply'), '', 0));
    
    //--- enrol instance defaults ----------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_manual_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));
    
    $settings->add(new admin_setting_configcheckbox('enrol_apply/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 0));
    
    $options = array(ENROL_INSTANCE_ENABLED => get_string('yes'),
                     ENROL_INSTANCE_DISABLED  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_apply/status',
        get_string('status', 'enrol_apply'), get_string('status_desc', 'enrol_apply'), ENROL_INSTANCE_ENABLED, $options));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_apply/roleid',
            get_string('defaultrole', 'role'), '', $student->id, $options));
    }
}

if ($hassiteconfig) { // needs this condition or there is error on login page
    $ADMIN->add('courses', new admin_externalpage('enrol_apply',
            get_string('applymanage', 'enrol_apply'),
            new moodle_url('/enrol/apply/manage.php')));
}

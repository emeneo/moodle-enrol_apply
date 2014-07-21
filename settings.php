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
    $settings->add(new admin_setting_configcheckbox('enrol_apply/sendmailtomanager',
        get_string('sendmailtomanager', 'enrol_apply'), '', 0));


    //--- enrol instance defaults ----------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_apply_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_apply/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 1));

    $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                     ENROL_INSTANCE_DISABLED => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_apply/status',
        get_string('status', 'enrol_apply'), get_string('status_desc', 'enrol_apply'), ENROL_INSTANCE_DISABLED, $options));

    $settings->add(new admin_setting_configtext('enrol_apply/name',
        get_string('custominstancename', 'enrol'), '', '', PARAM_TEXT));

    $settings->add(new admin_setting_configtextarea('enrol_apply/customtext1',
        get_string('editdescription', 'enrol_apply'), '', '', PARAM_TEXT));

}

if ($hassiteconfig) { // needs this condition or there is error on login page
    $ADMIN->add('courses', new admin_externalpage('enrol_apply',
            get_string('applymanage', 'enrol_apply'),
            new moodle_url('/enrol/apply/manage.php')));
}

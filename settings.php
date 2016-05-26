<?php
/**
 * *************************************************************************
 * *                  Apply Enrol                                         **
 * *************************************************************************
 * @copyright   emeneo.com                                                **
 * @link        emeneo.com                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************
*/
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('enrol_apply_enrolname','',get_string('pluginname_desc', 'enrol_apply')));
    
    // Confirm mail settings.
    $settings->add(new admin_setting_heading(
        'enrol_apply_confirmmail',
        get_string('confirmmail_heading', 'enrol_apply'),
        get_string('confirmmail_desc', 'enrol_apply')));
    $settings->add(new admin_setting_configtext(
        'enrol_apply/confirmmailsubject',
        get_string('confirmmailsubject', 'enrol_apply'),
        get_string('confirmmailsubject_desc', 'enrol_apply'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_apply/confirmmailcontent',
        get_string('confirmmailcontent', 'enrol_apply'),
        get_string('confirmmailcontent_desc', 'enrol_apply'),
        null,
        PARAM_TEXT));

    // Wait mail settings.
    $settings->add(new admin_setting_heading(
        'enrol_apply_waitmail',
        get_string('waitmail_heading', 'enrol_apply'),
        get_string('waitmail_desc', 'enrol_apply')));
    $settings->add(new admin_setting_configtext(
        'enrol_apply/waitmailsubject',
        get_string('waitmailsubject', 'enrol_apply'),
        get_string('waitmailsubject_desc', 'enrol_apply'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_apply/waitmailcontent',
        get_string('waitmailcontent', 'enrol_apply'),
        get_string('waitmailcontent_desc', 'enrol_apply'),
        null,
        PARAM_TEXT));

    // Cancel mail settings
    $settings->add(new admin_setting_heading(
        'enrol_apply_cancelmail',
        get_string('cancelmail_heading', 'enrol_apply'),
        get_string('cancelmail_desc', 'enrol_apply')));
    $settings->add(new admin_setting_configtext(
        'enrol_apply/cancelmailsubject',
        get_string('cancelmailsubject', 'enrol_apply'),
        get_string('cancelmailsubject_desc', 'enrol_apply'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_apply/cancelmailcontent',
        get_string('cancelmailcontent', 'enrol_apply'),
        get_string('cancelmailcontent_desc', 'enrol_apply'),
        null,
        PARAM_TEXT));

    // Notification settings.
    $settings->add(new admin_setting_heading(
        'enrol_apply_notify',
        get_string('notify_heading', 'enrol_apply'),
        get_string('notify_desc', 'enrol_apply')));
    $settings->add(new admin_setting_configcheckbox(
        'enrol_apply/sendmailtoteacher',
        get_string('sendmailtoteacher', 'enrol_apply'),
        '',
        0));
    $settings->add(new admin_setting_configcheckbox(
        'enrol_apply/sendmailtomanager',
        get_string('sendmailtomanager', 'enrol_apply'),
        '',
        0));

    // Enrol instance defaults.
    $settings->add(new admin_setting_heading('enrol_manual_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_apply/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 0));

    $options = array(ENROL_INSTANCE_ENABLED => get_string('yes'),
                     ENROL_INSTANCE_DISABLED  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_apply/status',
        get_string('status', 'enrol_apply'), get_string('status_desc', 'enrol_apply'), ENROL_INSTANCE_ENABLED, $options));

    $options = array(1 => get_string('yes'),
                     0  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_apply/show_standard_user_profile',
        get_string('show_standard_user_profile', 'enrol_apply'), '', 1, $options));

    $options = array(1 => get_string('yes'),
                     0  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_apply/show_extra_user_profile',
        get_string('show_extra_user_profile', 'enrol_apply'), '', 1, $options));

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

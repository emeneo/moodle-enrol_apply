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
require('../../config.php');
require_once('edit_form.php');

$courseid   = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT); // instanceid

$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/self:config', $context);

$PAGE->set_url('/enrol/apply/edit.php', array('courseid'=>$course->id, 'id'=>$instanceid));
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', array('id'=>$course->id));
if (!enrol_is_enabled('apply')) {
    redirect($return);
}

$plugin = enrol_get_plugin('apply');

if ($instanceid) {
    $instance = $DB->get_record('enrol', array('courseid'=>$course->id, 'enrol'=>'apply', 'id'=>$instanceid), '*', MUST_EXIST);
} else {
    require_capability('moodle/course:enrolconfig', $context);
    // no instance yet, we have to add new instance
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));
    $instance = new stdClass();
    $instance->id       = null;
    $instance->courseid = $course->id;
}

$mform = new enrol_self_edit_form(NULL, array($instance, $plugin, $context));

if ($mform->is_cancelled()) {
    redirect($return);

} else if ($data = $mform->get_data()) {
    if ($instance->id) {
        $instance->status         = $data->status;
        $instance->name           = $data->name;
        $instance->password       = $data->password;
        $instance->customint1     = $data->customint1;
        $instance->customint2     = $data->customint2;
        $instance->customint3     = $data->customint3;
        $instance->customint4     = $data->customint4;
        $instance->customtext1    = $data->customtext1;
        $instance->roleid         = $data->roleid;
        $instance->enrolperiod    = $data->enrolperiod;
        $instance->enrolstartdate = $data->enrolstartdate;
        $instance->enrolenddate   = $data->enrolenddate;
        $instance->timemodified   = time();
        $DB->update_record('enrol', $instance);

    } else {
//        $fields = array('status'=>$data->status, 'name'=>$data->name, 'password'=>$data->password, 'customint1'=>$data->customint1, 'customint2'=>$data->customint2,
//                        'customint3'=>$data->customint3, 'customint4'=>$data->customint4, 'customtext1'=>$data->customtext1,
//                        'roleid'=>$data->roleid, 'enrolperiod'=>$data->enrolperiod, 'enrolstartdate'=>$data->enrolstartdate, 'enrolenddate'=>$data->enrolenddate);
        $fields = array('status'=>$data->status, 'name'=>$data->name, 'customtext1'=>$data->customtext1);
        $plugin->add_instance($course, $fields);
    }

    redirect($return);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_apply'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_apply'));
$mform->display();
echo $OUTPUT->footer();

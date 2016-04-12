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

require_once ('../../config.php');
require_once($CFG->dirroot.'/enrol/apply/lib.php');

$id = optional_param('id', null, PARAM_INT);
$userenrolments = optional_param_array('userenrolments', null, PARAM_INT);

require_login();

$manageurlparams = array();
if ($id == null) {
    $context = context_system::instance();
    require_capability('enrol/apply:manage', $context);
    $pageheading = get_string('confirmusers', 'enrol_apply');
} else {
    $instance = $DB->get_record('enrol', array('id'=>$id, 'enrol'=>'apply'), '*', MUST_EXIST);
    require_course_login($instance->courseid);
    $course = get_course($instance->courseid);
    $context = context_course::instance($course->id, MUST_EXIST);
    require_capability('moodle/course:enrolreview', $context);
    $manageurlparams['id'] = $instance->id;
    $pageheading = $course->fullname;
}

$manageurl = new moodle_url('/enrol/apply/manage.php', $manageurlparams);

$PAGE->set_context($context);
$PAGE->set_url($manageurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading($pageheading);
$PAGE->navbar->add(get_string('confirmusers', 'enrol_apply'));
$PAGE->set_title(get_string('confirmusers', 'enrol_apply'));

if ($userenrolments != null) {
    $action = required_param('type', PARAM_TEXT);
    switch ($action) {
        case 'confirm':
            confirmEnrolment($userenrolments);
            break;
        case 'wait':
            waitEnrolment ($userenrolments);
            break;
        case 'cancel':
            cancelEnrolment($userenrolments);
            break;
        default:
            # code...
            break;
    }
    redirect($manageurl);
}

$enrols = getAllEnrolment($id);
if ($id == null) {
    $applicationinfo = $DB->get_records_sql('
        SELECT userenrolmentid, comment
        FROM {enrol_apply_applicationinfo}
        WHERE userenrolmentid IN (
            SELECT id
            FROM {user_enrolments}
            WHERE enrolid IN (
                SELECT id
                FROM {enrol}
                WHERE enrol = ?))', array('apply'));
} else {
    $applicationinfo = $DB->get_records_sql('
        SELECT userenrolmentid, comment
        FROM {enrol_apply_applicationinfo}
        WHERE userenrolmentid IN (
            SELECT id
            FROM {user_enrolments}
            WHERE enrolid = ?)', array($instance->id));
}

echo $OUTPUT->header ();
echo $OUTPUT->heading ( get_string ( 'confirmusers', 'enrol_apply' ) );
echo get_string('confirmusers_desc', 'enrol_apply');
echo '<form id="frmenrol" method="post" action="manage.php?id=' . $id . '">';
echo '<input type="hidden" id="type" name="type" value="confirm">';
echo '<table class="generalbox editcourse boxaligncenter"><tr class="header">';
echo '<th class="header" scope="col">&nbsp;</th>';
echo '<th class="header" scope="col">' . get_string ( 'coursename', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">&nbsp;</th>';
echo '<th class="header" scope="col">' . get_string ( 'applyuser', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applyusermail', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applydate', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'comment', 'enrol_apply' ) . '</th>';
echo '</tr>';
foreach ( $enrols as $enrol ) {
    $picture = get_user_picture($enrol->userid);
    if ($enrol->status == 2) {
        echo '<tr style="vertical-align: top; background-color: #ccc;">';
    } else {
        echo '<tr style="vertical-align: top;">';
    }
    echo '<td><input type="checkbox" name="userenrolments[]" value="' . $enrol->id . '"></td>';
    echo '<td>' . format_string($enrol->course) . '</td>';
    echo '<td>' . $OUTPUT->render($picture) . '</td>';
    echo '<td>'.$enrol->firstname . ' ' . $enrol->lastname.'</td>';
    echo '<td>' . $enrol->email . '</td>';
    echo '<td>' . date ( "Y-m-d", $enrol->timecreated ) . '</td>';
    echo '<td>' . htmlspecialchars($applicationinfo[$enrol->id]->comment) . '</td>';
    echo '</tr>';
}
echo '</table>';
echo '<p align="center">';
echo '<input type="button" value="' . get_string ( 'btnconfirm', 'enrol_apply' ) . '" onclick="doSubmit(\'confirm\');">';
echo '<input type="button" value="' . get_string ( 'btnwait', 'enrol_apply' ) . '" onclick="doSubmit(\'wait\');">';
echo '<input type="button" value="' . get_string ( 'btncancel', 'enrol_apply' ) . '" onclick="doSubmit(\'cancel\');">';
echo '</p>';
echo '</form>';
echo '<script>function doSubmit(type){
    document.getElementById("type").value=type;
    document.getElementById("frmenrol").submit();
}</script>';
echo $OUTPUT->footer ();

function get_user_picture($userid){
    global $DB;

    $extrafields[] = 'lastaccess';
    $ufields = user_picture::fields('u', $extrafields);
    $sql = "SELECT DISTINCT $ufields FROM {user} u where u.id=$userid";
          
    $user = $DB->get_record_sql($sql);
    return new user_picture($user);
}
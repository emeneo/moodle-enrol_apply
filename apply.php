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
require ('../../config.php');
require_once($CFG->dirroot.'/enrol/renderer.php');
require_once($CFG->dirroot.'/enrol/locallib.php');
require_once($CFG->dirroot.'/lib/outputcomponents.php');
require_once ('lib.php');

$site = get_site ();
$systemcontext = context_system::instance();

$id = required_param ( 'id', PARAM_INT ); // course id
$course = $DB->get_record ( 'course', array ('id' => $id ), '*', MUST_EXIST );
$context =  context_course::instance($course->id, MUST_EXIST);

require_login ( $course );
require_capability ( 'moodle/course:enrolreview', $context );

$PAGE->set_url ( '/enrol/apply.php', array ('id' => $course->id ) );
//$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout ( 'admin' );
$PAGE->set_heading ( $course->fullname );

$PAGE->navbar->add ( get_string ( 'confirmusers', 'enrol_apply' ) );
$PAGE->set_title ( "$site->shortname: " . get_string ( 'confirmusers', 'enrol_apply' ) );

if (isset ( $_POST ['enrolid'] )) {
	if ($_POST ['enrolid']) {
		if ($_POST ['type'] == 'confirm') {
			confirmEnrolment ( $_POST ['enrolid'] );
		} elseif ($_POST ['type'] == 'cancel') {
			cancelEnrolment ( $_POST ['enrolid'] );
		}
		redirect ( "$CFG->wwwroot/enrol/apply/apply.php?id=" . $id . "&enrolid=" . $_GET ['enrolid'] );
	}
}

$enrols = getAllEnrolment ($id);

echo $OUTPUT->header ();
echo $OUTPUT->heading ( get_string ( 'confirmusers', 'enrol_apply' ) );
echo '<form id="frmenrol" method="post" action="apply.php?id=' . $id . '&enrolid=' . $_GET ['enrolid'] . '">';
echo '<input type="hidden" id="type" name="type" value="confirm">';
echo '<table class="generalbox editcourse boxaligncenter"><tr class="header">';
echo '<th class="header" scope="col">&nbsp;</th>';
echo '<th class="header" scope="col">' . get_string ( 'coursename', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">&nbsp;</th>';
echo '<th class="header" scope="col">' . get_string ( 'applyuser', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applyusermail', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applydate', 'enrol_apply' ) . '</th>';
echo '</tr>';
foreach ( $enrols as $enrol ) {
	$picture = get_user_picture($enrol->userid);
	echo '<tr><td><input type="checkbox" name="enrolid[]" value="' . $enrol->id . '"></td>';
	echo '<td>' . format_string($enrol->course) . '</td>';
	echo '<td>' . $OUTPUT->render($picture) . '</td>';
	echo '<td>'.$enrol->firstname . ' ' . $enrol->lastname.'</td>';
	echo '<td>' . $enrol->email . '</td>';
	echo '<td>' . date ( "Y-m-d", $enrol->timecreated ) . '</td></tr>';
}
echo '</table>';
echo '<p align="center"><input type="button" value="' . get_string ( 'btnconfirm', 'enrol_apply' ) . '" onclick="doSubmit(\'confrim\');">&nbsp;&nbsp;<input type="button" value="' . get_string ( 'btncancel', 'enrol_apply' ) . '" onclick="doSubmit(\'cancel\');"></p>';
echo '</form>';
echo '<script>function doSubmit(type){if(type=="cancel"){document.getElementById("type").value=type;}document.getElementById("frmenrol").submit();}</script>';
echo $OUTPUT->footer ();


function get_user_picture($userid){
	global $DB;

    $extrafields[] = 'lastaccess';
    $ufields = user_picture::fields('u', $extrafields);
	$sql = "SELECT DISTINCT $ufields FROM {user} u where u.id=$userid";
          
    $user = $DB->get_record_sql($sql);
	return new user_picture($user);
}
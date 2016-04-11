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

$enrolid = optional_param('enrolid', 0, PARAM_INT);

require_login ( $course );
require_capability ( 'moodle/course:enrolreview', $context );

$PAGE->set_url ( '/enrol/apply.php', array ('id' => $course->id ) );
//$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout ( 'admin' );
$PAGE->set_heading ( $course->fullname );

$PAGE->navbar->add ( get_string ( 'confirmusers', 'enrol_apply' ) );
$PAGE->set_title ( "$site->shortname: " . get_string ( 'confirmusers', 'enrol_apply' ) );

$userenrolments = optional_param_array('userenrolments', null, PARAM_INT);
if ($userenrolments != null) {
	if ($_POST ['type'] == 'confirm') {
		confirmEnrolment($userenrolments);
	} elseif ($_POST ['type'] == 'wait') {
		waitEnrolment ($userenrolments);
	} elseif ($_POST ['type'] == 'cancel') {
		cancelEnrolment($userenrolments);
	}
	redirect ( "$CFG->wwwroot/enrol/apply/apply.php?id=" . $id . "&enrolid=" . $enrolid );
}

$enrols = getAllEnrolment ($enrolid);
$applicationinfo = $DB->get_records_sql('
	SELECT userenrolmentid, comment
	FROM {enrol_apply_applicationinfo}
	WHERE userenrolmentid IN (
		SELECT id
		FROM {user_enrolments}
		WHERE enrolid = ?)', array($enrolid));

echo $OUTPUT->header ();
echo $OUTPUT->heading ( get_string ( 'confirmusers', 'enrol_apply' ) );
echo get_string('confirmusers_desc', 'enrol_apply');
echo '<form id="frmenrol" method="post" action="apply.php?id=' . $id . '&enrolid=' . $enrolid . '">';
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
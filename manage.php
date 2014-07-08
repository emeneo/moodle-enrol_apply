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
require_once ('lib.php');
require_login();
require_capability('enrol/apply:manage', context_system::instance());

$site = get_site ();
$systemcontext = context_system::instance();

$PAGE->set_url ( '/enrol/manage.php');
$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout ( 'admin' );
//$PAGE->set_heading ( $course->fullname );

$PAGE->navbar->add ( get_string ( 'confirmusers', 'enrol_apply' ) );
$PAGE->set_title ( "$site->shortname: " . get_string ( 'confirmusers', 'enrol_apply' ) );

if (isset ( $_POST ['enrolid'] )) {
	if ($_POST ['enrolid']) {
		if ($_POST ['type'] == 'confirm') {
			confirmEnrolment ( $_POST ['enrolid'] );
		} elseif ($_POST ['type'] == 'cancel') {
			cancelEnrolment ( $_POST ['enrolid'] );
		}
		redirect ( "$CFG->wwwroot/enrol/apply/manage.php" );
	}
}

$enrols = getAllEnrolment();
echo $OUTPUT->header ();
echo $OUTPUT->heading ( get_string ( 'confirmusers', 'enrol_apply' ) );
echo '<form id="frmenrol" method="post" action="manage.php">';
echo '<input type="hidden" id="type" name="type" value="confirm">';
echo '<table class="generalbox editcourse boxaligncenter"><tr class="header">';
echo '<th class="header" scope="col">&nbsp;</th>';
echo '<th class="header" scope="col">' . get_string ( 'coursename', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applyuser', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applyusermail', 'enrol_apply' ) . '</th>';
echo '<th class="header" scope="col">' . get_string ( 'applydate', 'enrol_apply' ) . '</th>';
echo '</tr>';
foreach ( $enrols as $enrol ) {
	echo '<tr><td><input type="checkbox" name="enrolid[]" value="' . $enrol->id . '"></td>';
	echo '<td>' . format_string($enrol->course) . '</td>';
	echo '<td>' . $enrol->firstname . ' ' . $enrol->lastname . '</td>';
	echo '<td>' . $enrol->email . '</td>';
	echo '<td>' . date ( "Y-m-d", $enrol->timecreated ) . '</td></tr>';
}
echo '</table>';
echo '<p align="center"><input type="button" value="' . get_string ( 'btnconfirm', 'enrol_apply' ) . '" onclick="doSubmit(\'confrim\');">&nbsp;&nbsp;<input type="button" value="' . get_string ( 'btncancel', 'enrol_apply' ) . '" onclick="doSubmit(\'cancel\');"></p>';
echo '</form>';
echo '<script>function doSubmit(type){if(type=="cancel"){document.getElementById("type").value=type;}document.getElementById("frmenrol").submit();}</script>';
echo $OUTPUT->footer ();
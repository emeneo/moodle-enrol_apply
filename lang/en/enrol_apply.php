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
// The name of your plugin. Displayed on admin menus.
$string['enrolname'] = 'Course enrol confirmation';
$string['pluginname'] = 'Course enrol confirmation';
$string['pluginname_desc'] = 'With this plugin users can apply to a course and a teacher have to comfirm before the user gets enroled.';

$string['confirmmailsubject'] = 'Confirm mail subject';
$string['confirmmailcontent'] = 'Confirm mail content';
$string['cancelmailsubject'] = 'Cancel mail subject';
$string['cancelmailcontent'] = 'Cancel mail content';
$string['confirmmailcontent_desc'] = 'Please use special marks designated email content replaced.<br>{firstname}:Registration name; {content}:Course name;{lastname}:The last name of the user;{username}:Registration name';
$string['cancelmailcontent_desc'] = 'Please use special marks designated email content replaced.<br>{firstname}:Registration name; {content}:Course name;{lastname}:The last name of the user;{username}:Registration name';

$string['confirmusers'] = 'Enrol Confirm';

$string['coursename'] = 'Course';
$string['applyuser'] = 'First name / Surname';
$string['applyusermail'] = 'Email';
$string['applydate'] = 'Enrol date';
$string['btnconfirm'] = 'Confirm';
$string['btncancel'] = 'Cancel';
$string['enrolusers'] = 'Enrol users';

$string['status'] = 'Allow Course enrol confirmation';
$string['confirmenrol'] = 'Manage application';

$string['apply:config'] = 'Configure apply enrol instances';
$string['apply:manage'] = 'Manage apply enrolment';
$string['apply:unenrol'] = 'Cancel users from course';
$string['apply:unenrolapply'] = 'Cancel self from the course'; // is this necessary now?
$string['apply:unenrolself'] = 'Cancel self from the course';
 
$string['notification'] = '<b>Enrollment Application successfully sent</b>. <br/><br/>You will be informed by email as soon as your enrollment has been confirmed. If you want to enroll to other courses, please click "course catalogue" in the top menu.';

$string['sendmailtoteacher'] = 'Send email notification to teachers';
$string['sendmailtomanager'] = 'Send email notification to managers';
$string['mailtoteacher_suject'] = 'New Enrollment request!';
$string['editdescription'] = 'Textarea description';
$string['comment'] = 'Comment';
$string['applymanage'] = 'Manage enrolment applications';

$string['status_desc'] = 'Allow course access of internally enrolled users.';

?>
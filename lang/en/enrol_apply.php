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
// The name of your plug-in. Displayed on admin menus.
$string['enrolname'] = 'Course enrol confirmation';
$string['pluginname'] = 'Course enrol confirmation';
$string['pluginname_desc'] = 'With this plug-in users can apply to be enrolled in a course. A teacher or site manager will then have to approve the enrolment before the user gets enroled.';

$string['confirmmailsubject'] = 'Confirmation email subject';
$string['confirmmailcontent'] = 'Confirmation email content';
$string['cancelmailsubject'] = 'Cancelation email subject';
$string['cancelmailcontent'] = 'Cancelation email content';
$string['confirmmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username';
$string['cancelmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username';

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
$string['apply:unenrol'] = 'Cancel users from the course';
$string['apply:unenrolapply'] = 'Cancel self from the course'; // is this necessary now?
$string['apply:unenrolself'] = 'Cancel self from the course';

 
$string['notification'] = '<b>Enrolment application successfully sent</b>. <br/><br/>You will be informed by email when your enrolment has been confirmed.';

$string['sendmailtoteacher'] = 'Send email notification to teachers';
$string['sendmailtomanager'] = 'Send email notification to managers';
$string['mailtoteacher_suject'] = 'New Enrolment request!';
$string['editdescription'] = 'Textarea description';
$string['comment'] = 'Comment';
$string['applymanage'] = 'Manage enrolment applications';

$string['status_desc'] = 'Allow course access of internally enrolled users.';
$string['user_profile'] = 'User Profile';
?>
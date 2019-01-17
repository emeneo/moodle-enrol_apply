<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    enrol_apply
 * @copyright  emeneo.com (http://emeneo.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     emeneo.com (http://emeneo.com/)
 * @author     Johannes Burk <johannes.burk@sudile.com>
 */

// The name of your plug-in. Displayed on admin menus.
$string['enrolname'] = 'Course enrol confirmation';
$string['pluginname'] = 'Course enrol confirmation';
$string['pluginname_desc'] = 'With this plug-in users can apply to be enrolled in a course. A teacher or site manager will then have to approve the enrolment before the user gets enroled.';

$string['confirmmail_heading'] = 'Confirmation email';
$string['confirmmail_desc'] = '';
$string['confirmmailsubject'] = 'Confirmation email subject';
$string['confirmmailsubject_desc'] = '';
$string['confirmmailcontent'] = 'Confirmation email content';
$string['confirmmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username;{timeend}: The enrolment expiration date';

$string['waitmail_heading'] = 'Waiting list email';
$string['waitmail_desc'] = '';
$string['waitmailsubject'] = 'Waiting list mail subject';
$string['waitmailsubject_desc'] = '';
$string['waitmailcontent'] = 'Waiting list mail content';
$string['waitmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username';

$string['cancelmail_heading'] = 'Cancelation email';
$string['cancelmail_desc'] = '';
$string['cancelmailsubject'] = 'Cancelation email subject';
$string['cancelmailsubject_desc'] = '';
$string['cancelmailcontent'] = 'Cancelation email content';
$string['cancelmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username';

$string['notify_heading'] = 'Notification settings';
$string['notify_desc'] = 'Define who gets notified about new enrolment applications.';
$string['notifycoursebased'] = "New enrolment application notification (instance based, eg. course teachers)";
$string['notifycoursebased_desc'] = "Default for new instances: Notify everyone who have the 'Manage apply enrolment' capability for the corresponding course (eg. teachers and managers)";
$string['notifyglobal'] = "New enrolment application notification (global, eg. global managers and admins)";
$string['notifyglobal_desc'] = "Define who gets notified about new enrolment applications for any course.";

$string['messageprovider:application'] = 'Course enrolment application notifications';
$string['messageprovider:confirmation'] = 'Course enrolment application confirmation notifications';
$string['messageprovider:cancelation'] = 'Course enrolment application cancelation notifications';
$string['messageprovider:waitinglist'] = 'Course enrolment application defer notifications';

$string['newapplicationnotification'] = 'There is a new course enrolment application awaiting review.';
$string['applicationconfirmednotification'] = 'Your course enrolment application was confirmed.';
$string['applicationcancelednotification'] = 'Your course enrolment application was canceled.';
$string['applicationdeferrednotification'] = 'Your course enrolment application was deferred (you are currently on the waiting list).';

$string['confirmusers'] = 'Enrol Confirm';
$string['confirmusers_desc'] = 'Users in gray colored rows are on the waiting list.';

$string['coursename'] = 'Course';
$string['applyuser'] = 'First name / Surname';
$string['applyusermail'] = 'Email';
$string['applydate'] = 'Enrol date';
$string['btnconfirm'] = 'Confirm requests';
$string['btnwait'] = 'Defer requests';
$string['btncancel'] = 'Cancel requests';
$string['enrolusers'] = 'Enrol users';

$string['status'] = 'Allow Course enrol confirmation';
$string['newenrols'] = 'Allow new course enrol request';
$string['confirmenrol'] = 'Manage application';

$string['apply:config'] = 'Configure apply enrol instances';
$string['apply:manage'] = 'Manage user enrolments';
$string['apply:manageapplications'] = 'Manage apply enrolment';
$string['apply:unenrol'] = 'Cancel users from the course';
$string['apply:unenrolself'] = 'Cancel self from the course';

$string['notification'] = '<b>Enrolment application successfully sent</b>. <br/><br/>You will be informed by email when your enrolment has been confirmed.';

$string['mailtoteacher_suject'] = 'New Enrolment request!';
$string['editdescription'] = 'Textarea description';
$string['comment'] = 'Comment';
$string['applymanage'] = 'Manage enrolment applications';

$string['status_desc'] = 'Allow course access of internally enrolled users.';
$string['user_profile'] = 'User Profile';

$string['show_standard_user_profile'] = 'Show standard user profile fields on enrolment screen';
$string['show_extra_user_profile'] = 'Show extra user profile fields on enrolment screen';

//$string['custom_label'] = 'Custom label "{replace_title}"';
$string['custom_label'] = 'Custom label';

$string['maxenrolled'] = 'Max enrolled users';
$string['maxenrolled_help'] = 'Specifies the maximum number of users that can self enrol. 0 means no limit.';
$string['maxenrolledreached_left'] = 'Maximum number of users allowed';
$string['maxenrolledreached_right'] = 'has already been reached.';

$string['cantenrol'] = 'Enrolment is disabled or inactive';

$string['maxenrolled_tip_1'] = 'out of';
$string['maxenrolled_tip_2'] = 'seats already booked.';

$string['defaultperiod'] = 'Default enrolment duration';
$string['defaultperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['defaultperiod_help'] = 'Default length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited by default.';
$string['expiry_heading'] = 'Expiry settings';
$string['expiry_desc'] = '';
$string['expiredaction'] = 'Enrolment expiry action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';

$string['submitted_info'] = 'Enrol info';

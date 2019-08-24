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
 */

// The name of your plug-in. Displayed on admin menus.
$string['enrollname'] = 'Course enrollment confirmation';
$string['pluginname'] = 'Course enrollment confirmation';
$string['pluginname_desc'] = 'With this plug-in users can apply to be enrolled in a course. A teacher or site manager will then have to approve the enrollment before the user gets enrolled.';

$string['confirmmailsubject'] = 'Confirmation email subject';
$string['confirmmailcontent'] = 'Confirmation email content';
$string['cancelmailsubject'] = 'Cancellation email subject';
$string['cancelmailcontent'] = 'Cancellation email content';
$string['confirmmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username';
$string['cancelmailcontent_desc'] = 'Please use the following special marks to replace email content with data from Moodle.<br/>{firstname}:The first name of the user; {content}:The course name;{lastname}:The last name of the user;{username}:The users registration username';

$string['confirmusers'] = 'Enroll confirm';

$string['coursename'] = 'Course';
$string['applyuser'] = 'First name / Surname';
$string['applyusermail'] = 'Email';
$string['applydate'] = 'Enroll date';
$string['btnconfirm'] = 'Confirm requests';
$string['btncancel'] = 'Cancel requests';
$string['enrollusers'] = 'Enroll users';

$string['status'] = 'Allow course enroll confirmation';
$string['confirmenroll'] = 'Manage application';

$string['apply:config'] = 'Configure apply enroll instances';
$string['apply:manageapplications'] = 'Manage apply enrollment';
$string['apply:unenroll'] = 'Cancel users from the course';
$string['apply:unenrollapply'] = 'Cancel self from the course';

$string['notification'] = '<b>Enrolllment application successfully sent</b>. <br/><br/>You will be informed by email when your enrollment has been confirmed.';

$string['sendmailtoteacher'] = 'Send email notification to teachers';
$string['sendmailtomanager'] = 'Send email notification to managers';
$string['mailtoteacher_suject'] = 'New enrollment request!';
$string['editdescription'] = 'Textarea description';
$string['comment'] = 'Comment';
$string['applycomment'] = 'Comment';
$string['applymanage'] = 'Manage enrollment applications';

$string['status_desc'] = 'Allow course access of internally enrolled users.';

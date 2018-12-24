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

/** The user is put onto a waiting list and therefore the enrolment not active (used in user_enrolments->status) */
define('ENROL_APPLY_USER_WAIT', 2);

class enrol_apply_plugin extends enrol_plugin {

    /**
     * Add new instance of enrol plugin with default settings.
     * @param object $course
     * @return int id of new instance
     */
    public function add_default_instance($course) {
        $fields = $this->get_instance_defaults();
        return $this->add_instance($course, $fields);
    }

    public function allow_unenrol(stdClass $instance) {
        // Users with unenrol cap may unenrol other users manually.
        return true;
    }
    public function roles_protected() {
        // Users may tweak the roles later.
        return false;
    }

    public function allow_apply(stdClass $instance) {
        if ($instance->status != ENROL_INSTANCE_ENABLED) {
            return get_string('cantenrol', 'enrol_apply');
        }
        if (!$instance->customint6) {
            // New enrols not allowed.
            return get_string('cantenrol', 'enrol_apply');
        }
        return true;
    }
    /**
     * Prevent to unenrol an user with a pending application
     *
     * @param stdClass $instance course enrol instance
     * @param stdClass $ue record from user_enrolments table, specifies user
     * @return bool
     */
    public function allow_unenrol_user(stdClass $instance, stdClass $ue) {
        global $DB;
        if ($DB->record_exists('enrol_apply_applicationinfo', ['userenrolmentid' => $ue->id])) {
            return false;
        }
        return parent::allow_unenrol_user($instance, $ue);
    }
    public function allow_manage(stdClass $instance) {
        // Users with manage cap may tweak period and status.
        return true;
    }
    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     * Multiple instances supported.
     * @param int $courseid
     * @return moodle_url page url
     */
    public function get_newinstance_link($courseid) {
        $context = context_course::instance($courseid, MUST_EXIST);

        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/apply:config', $context)) {
            return null;
        }
        return new moodle_url('/enrol/apply/edit.php', array('courseid' => $courseid));
    }

    public function enrol_page_hook(stdClass $instance) {
        global $CFG, $OUTPUT, $SESSION, $USER, $DB;

        if (isguestuser()) {
            // Can not enrol guest!
            return null;
        }

        $allowapply = $this->allow_apply($instance);
        if ($allowapply !== true) {
            return '<div class="alert alert-error">' . $allowapply . '</div>';
        }

        if ($DB->record_exists('user_enrolments', array('userid' => $USER->id, 'enrolid' => $instance->id))) {
            return $OUTPUT->notification(get_string('notification', 'enrol_apply'), 'notifysuccess');
        }

        if ($instance->customint3 > 0) {
            // Max enrol limit specified.
            $count = $DB->count_records('user_enrolments', array('enrolid' => $instance->id));
            if ($count >= $instance->customint3) {
                // Bad luck, no more self enrolments here.
                return '<div class="alert alert-error">'.get_string('maxenrolledreached_left', 'enrol_apply')." (".$count.") ".get_string('maxenrolledreached_right', 'enrol_apply').'</div>';
            }
        }

        require_once("$CFG->dirroot/enrol/apply/apply_form.php");

        $form = new enrol_apply_apply_form(null, $instance);

        if ($data = $form->get_data()) {
            // Only process when form submission is for this instance (multi instance support).
            if ($data->instance == $instance->id) {
                $timestart = 0;
                $timeend = 0;
                $roleid = $instance->roleid;

                $this->enrol_user($instance, $USER->id, $roleid, $timestart, $timeend, ENROL_USER_SUSPENDED);
                $userenrolment = $DB->get_record(
                    'user_enrolments',
                    array(
                        'userid' => $USER->id,
                        'enrolid' => $instance->id),
                    'id', MUST_EXIST);
                $applicationinfo = new stdClass();
                $applicationinfo->userenrolmentid = $userenrolment->id;
                $applicationinfo->comment = $data->applydescription;
                $DB->insert_record('enrol_apply_applicationinfo', $applicationinfo, false);

                $this->send_application_notification($instance, $USER->id, $data);

                redirect("$CFG->wwwroot/course/view.php?id=$instance->courseid");
            }
        }

        $output = $form->render();

        return $OUTPUT->box($output);
    }

    public function get_action_icons(stdClass $instance) {
        global $OUTPUT;

        if ($instance->enrol !== 'apply') {
            throw new coding_exception('invalid enrol instance!');
        }
        $context = context_course::instance($instance->courseid);

        $icons = array();

        if (has_capability('enrol/apply:config', $context)) {
            $editlink = new moodle_url("/enrol/apply/edit.php", array('courseid' => $instance->courseid, 'id' => $instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon(
                't/edit',
                get_string('edit'),
                'core',
                array('class' => 'iconsmall')));
        }

        if (has_capability('enrol/apply:manageapplications', $context)) {
            $managelink = new moodle_url("/enrol/apply/manage.php", array('id' => $instance->id));
            $icons[] = $OUTPUT->action_icon($managelink, new pix_icon(
                'i/users',
                get_string('confirmenrol', 'enrol_apply'),
                'core',
                array('class' => 'iconsmall')));
        }

        return $icons;
    }

    /**
     * Is it possible to hide/show enrol instance via standard UI?
     * @param  stdClass $instance
     * @return bool
     */
    public function can_hide_show_instance($instance) {
            $context = context_course::instance($instance->courseid);
            return has_capability('enrol/apply:config', $context);
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_delete_instance($instance) {
            $context = context_course::instance($instance->courseid);
            return has_capability('enrol/apply:config', $context);
    }

    /**
     * Sets up navigation entries.
     *
     * @param stdClass $instancesnode
     * @param stdClass $instance
     * @return void
     */
    public function add_course_navigation($instancesnode, stdClass $instance) {
        if ($instance->enrol !== 'apply') {
             throw new coding_exception('Invalid enrol instance type!');
        }

        $context = context_course::instance($instance->courseid);
        if (has_capability('enrol/apply:config', $context)) {
            $managelink = new moodle_url('/enrol/apply/edit.php', array('courseid' => $instance->courseid, 'id' => $instance->id));
            $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);
        }
    }

    public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol_user($instance, $ue) && has_capability("enrol/apply:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(
                new pix_icon('t/delete', ''),
                get_string('unenrol', 'enrol'),
                $url,
                array('class' => 'unenrollink', 'rel' => $ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/apply:manage", $context)) {
            $url = new moodle_url('/enrol/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array('class'=>'editenrollink', 'rel'=>$ue->id));
        }
        return $actions;
    }

    /**
     * Returns defaults for new instances.
     * @return array
     */
    public function get_instance_defaults() {
        $fields = array();
        $fields['status']          = $this->get_config('status');
        $fields['roleid']          = $this->get_config('roleid', 0);
        $fields['customint1']      = $this->get_config('show_standard_user_profile');
        $fields['customint2']      = $this->get_config('show_extra_user_profile');
        $fields['customtext2']     = $this->get_config('notifycoursebased') ? '$@ALL@$' : '';
        $fields['enrolperiod']     = $this->get_config('enrolperiod', 0);

        return $fields;
    }

    public function confirm_enrolment($enrols) {
        global $DB;
        foreach ($enrols as $enrol) {
            $userenrolment = $DB->get_record_select(
                'user_enrolments',
                'id = :id AND (status = :enrolusersuspended OR status = :enrolapplyuserwait)',
                array(
                    'id' => $enrol,
                    'enrolusersuspended' => ENROL_USER_SUSPENDED,
                    'enrolapplyuserwait' => ENROL_APPLY_USER_WAIT),
                '*',
                MUST_EXIST);

            $instance = $DB->get_record('enrol', array('id' => $userenrolment->enrolid, 'enrol' => 'apply'), '*', MUST_EXIST);

            // Check privileges.
            $context = context_course::instance($instance->courseid, MUST_EXIST);
            if (!has_capability('enrol/apply:manageapplications', $context)) {
                continue;
            }

            // Set timestart and timeend if an enrolment duration is set.
            $userenrolment->timestart = time();
            $userenrolment->timeend   = 0;
            if ($instance->enrolperiod) {
                $userenrolment->timeend = $userenrolment->timestart + $instance->enrolperiod;
            }

            $this->update_user_enrol($instance, $userenrolment->userid, ENROL_USER_ACTIVE, $userenrolment->timestart, $userenrolment->timeend);
            $DB->delete_records('enrol_apply_applicationinfo', array('userenrolmentid' => $enrol));

            $this->notify_applicant(
                    $instance,
                    $userenrolment,
                    'confirmation',
                    get_config('enrol_apply', 'confirmmailsubject'),
                    get_config('enrol_apply', 'confirmmailcontent'));
        }
    }

    public function wait_enrolment($enrols) {
        global $DB;
        foreach ($enrols as $enrol) {
            $userenrolment = $DB->get_record(
                'user_enrolments',
                array('id' => $enrol, 'status' => ENROL_USER_SUSPENDED),
                '*', IGNORE_MISSING);

            if ($userenrolment != null) {
                $instance = $DB->get_record('enrol', array('id' => $userenrolment->enrolid, 'enrol' => 'apply'), '*', MUST_EXIST);

                // Check privileges.
                $context = context_course::instance($instance->courseid, MUST_EXIST);
                if (!has_capability('enrol/apply:manageapplications', $context)) {
                    continue;
                }

                $this->update_user_enrol($instance, $userenrolment->userid, ENROL_APPLY_USER_WAIT);

                $this->notify_applicant(
                    $instance,
                    $userenrolment,
                    'waitinglist',
                    get_config('enrol_apply', 'waitmailsubject'),
                    get_config('enrol_apply', 'waitmailcontent'));
            }
        }
    }

    public function cancel_enrolment($enrols) {
        global $DB;
        foreach ($enrols as $enrol) {
            $userenrolment = $DB->get_record_select(
                'user_enrolments',
                'id = :id AND (status = :enrolusersuspended OR status = :enrolapplyuserwait)',
                array(
                    'id' => $enrol,
                    'enrolusersuspended' => ENROL_USER_SUSPENDED,
                    'enrolapplyuserwait' => ENROL_APPLY_USER_WAIT),
                '*',
                MUST_EXIST);

            $instance = $DB->get_record('enrol', array('id' => $userenrolment->enrolid, 'enrol' => 'apply'), '*', MUST_EXIST);

            // Check privileges.
            $context = context_course::instance($instance->courseid, MUST_EXIST);
            if (!has_capability('enrol/apply:manageapplications', $context)) {
                continue;
            }

            $this->unenrol_user($instance, $userenrolment->userid);
            $DB->delete_records('enrol_apply_applicationinfo', array('userenrolmentid' => $enrol));

            $this->notify_applicant(
                $instance,
                $userenrolment,
                'cancelation',
                get_config('enrol_apply', 'cancelmailsubject'),
                get_config('enrol_apply', 'cancelmailcontent'));
        }
    }

    private function notify_applicant($instance, $userenrolment, $type, $subject, $content) {
        global $CFG;
        require_once($CFG->dirroot.'/enrol/apply/notification.php');
        // Required for course_get_url() function.
        require_once($CFG->dirroot.'/course/lib.php');

        $course = get_course($instance->courseid);
        $user = core_user::get_user($userenrolment->userid);

        $content = $this->update_mail_content($content, $course, $user, $userenrolment);

        $message = new enrol_apply_notification(
            $user,
            core_user::get_support_user(),
            $type,
            $subject,
            $content,
            course_get_url($course));
        message_send($message);
    }

    private function send_application_notification($instance, $userid, $data) {
        global $CFG, $PAGE;
        require_once($CFG->dirroot.'/enrol/apply/notification.php');
        // Required for course_get_url() function.
        require_once($CFG->dirroot.'/course/lib.php');

        $renderer = $PAGE->get_renderer('enrol_apply');

        $course = get_course($instance->courseid);
        $user = core_user::get_user($userid);
        $contact = core_user::get_support_user();

        // Include standard user profile fields?
        $standarduserfields = null;
        if ($instance->customint1) {
            $standarduserfields = clone $data;
            unset($standarduserfields->applydescription);
        }

        // Include extra user profile fields?
        $extrauserfields = null;
        if ($instance->customint2) {
            require_once($CFG->dirroot.'/user/profile/lib.php');
            profile_load_custom_fields($user);
            $extrauserfields = $user->profile;
        }

        // Send notification to users with manageapplications in course context (instance depending)?
        $courseuserstonotify = $this->get_notifycoursebased_users($instance);
        if (!empty($courseuserstonotify)) {
            $manageurl = new moodle_url("/enrol/apply/manage.php", array('id' => $instance->id));
            $content = $renderer->application_notification_mail_body(
                $course,
                $user,
                $manageurl,
                $data->applydescription,
                $standarduserfields,
                $extrauserfields);
            foreach ($courseuserstonotify as $user) {
                $message = new enrol_apply_notification(
                    $user,
                    $contact,
                    'application',
                    get_string('mailtoteacher_suject', 'enrol_apply'),
                    $content,
                    $manageurl);
                message_send($message);
            }
        }

        // Send notification to users with manageapplications in system context?
        $globaluserstonotify = $this->get_notifyglobal_users();
        $globaluserstonotify = array_udiff($globaluserstonotify, $courseuserstonotify, function($usera, $userb) {
            return $usera->id == $userb->id ? 0 : -1;
        });
        if (!empty($globaluserstonotify)) {
            $manageurl = new moodle_url('/enrol/apply/manage.php');
            $content = $renderer->application_notification_mail_body(
                $course,
                $user,
                $manageurl,
                $data->applydescription,
                $standarduserfields,
                $extrauserfields);
            foreach ($globaluserstonotify as $user) {
                $message = new enrol_apply_notification(
                    $user,
                    $contact,
                    'application',
                    get_string('mailtoteacher_suject', 'enrol_apply'),
                    $content,
                    $manageurl);
                message_send($message);
            }
        }
    }

    /**
     * Returns enrolled users of a course who should be notified about new course enrolment applications.
     *
     * Note: mostly copied from get_users_from_config() function in moodlelib.php.
     * @param  array $instance Enrol apply instance record.
     * @return array           Array of user IDs.
     */
    public function get_notifycoursebased_users($instance) {
        $value = $instance->customtext2;
        if (empty($value) or $value === '$@NONE@$') {
            return array();
        }

        $context = context_course::instance($instance->courseid);

        // We have to make sure that users still have the necessary capability,
        // it should be faster to fetch them all first and then test if they are present
        // instead of validating them one-by-one.
        $users = get_enrolled_users($context, 'enrol/apply:manageapplications');

        if ($value === '$@ALL@$') {
            return $users;
        }

        $result = array(); // Result in correct order.
        $allowed = explode(',', $value);
        foreach ($allowed as $uid) {
            if (isset($users[$uid])) {
                $user = $users[$uid];
                $result[$user->id] = $user;
            }
        }

        return $result;
    }

    /**
     * Returns users who should be notified about new course enrolment applications.
     * @return array Array of user IDs.
     */
    public function get_notifyglobal_users() {
        return get_users_from_config($this->get_config('notifyglobal'), 'enrol/apply:manageapplications');
    }

    private function update_mail_content($content, $course, $user, $userenrolment) {
        $replace = array(
            'firstname' => $user->firstname,
            'content'   => format_string($course->fullname),
            'lastname'  => $user->lastname,
            'username'  => $user->username,
            'timeend'   => !empty($userenrolment->timeend) ? userdate($userenrolment->timeend) : ''
        );
        foreach ($replace as $key => $val) {
            $content = str_replace('{' . $key . '}', $val, $content);
        }
        return $content;
    }

    /**
     * Backup execution step hook.
     *
     * @param backup_enrolments_execution_step $step
     * @param stdClass $enrol
     */
    public function backup_annotate_custom_fields(backup_enrolments_execution_step $step, stdClass $enrol) {
        // annotate customint1 as a role
        $step->annotate_id('role', $enrol->customint1);
    }

    public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
        global $DB, $CFG;

        $data->customint1 = $step->get_mappingid('role', $data->customint1, null);

        $instanceid = $this->add_instance($course, (array)$data);
        $step->set_mapping('enrol', $oldid, $instanceid);

        //$this->sync_enrols($DB->get_record('enrol', array('id'=>$instanceid)));
    }

    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus) {
        $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $oldinstancestatus);
    }
    /**
     * Enrol cron support.
     * @return void
     */
    public function cron() {
        $trace = new text_progress_trace();
        $this->process_expirations($trace);
    }
}

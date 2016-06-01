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

/** User participation in course is suspended (used in user_enrolments->status) */
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

    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     * Multiple instances supported.
     * @param int $courseid
     * @return moodle_url page url
     */
    public function get_newinstance_link($courseid) {
        $context =  context_course::instance($courseid, MUST_EXIST);

        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/apply:config', $context)) {
            return NULL;
        }
        return new moodle_url('/enrol/apply/edit.php', array('courseid'=>$courseid));
    }

    public function enrol_page_hook(stdClass $instance) {
        global $CFG, $OUTPUT, $SESSION, $USER, $DB;

        if (isguestuser()) {
            // can not enrol guest!!
            return null;
        }
        if ($DB->record_exists('user_enrolments', array('userid'=>$USER->id, 'enrolid'=>$instance->id))) {
            return $OUTPUT->notification(get_string('notification', 'enrol_apply'), 'notifysuccess');
        }

        require_once("$CFG->dirroot/enrol/apply/apply_form.php");

        $form = new enrol_apply_apply_form(NULL, $instance);

        if ($data = $form->get_data()) {
            // Only process when form submission is for this instance (multi instance support).
            if ($data->instance == $instance->id) {
                $userInfo = $data;
                $applydescription = $userInfo->applydescription;
                unset($userInfo->applydescription);
                $userInfo->id = $USER->id;

                $timestart = 0;
                $timeend = 0;
                $roleid = $instance->roleid;

                $this->enrol_user($instance, $USER->id, $roleid, $timestart, $timeend, ENROL_USER_SUSPENDED);
                $userenrolment = $DB->get_record('user_enrolments', array('userid' => $USER->id, 'enrolid' => $instance->id), 'id', MUST_EXIST);
                $applicationinfo = new stdClass();
                $applicationinfo->userenrolmentid = $userenrolment->id;
                $applicationinfo->comment = $applydescription;
                $DB->insert_record('enrol_apply_applicationinfo', $applicationinfo, false);
                $this->sendConfirmMailToTeachers($instance, $data, $applydescription);
                $this->sendConfirmMailToManagers($instance, $data, $applydescription);

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
        $context =  context_course::instance($instance->courseid);

        $icons = array();

        if (has_capability('enrol/apply:config', $context)) {
            $editlink = new moodle_url("/enrol/apply/edit.php", array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit'), 'core', array('class' => 'iconsmall')));
        }

        if (has_capability('enrol/apply:manageapplications', $context)) {
            $managelink = new moodle_url("/enrol/apply/manage.php", array('id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($managelink, new pix_icon('i/users', get_string('confirmenrol', 'enrol_apply'), 'core', array('class'=>'iconsmall')));
        }

        return $icons;
    }

       /**
    * Is it possible to hide/show enrol instance via standard UI?
    *
    * @param stdClass $instance
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
            $managelink = new moodle_url('/enrol/apply/edit.php', array('courseid'=>$instance->courseid, 'id'=>$instance->id));
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
        $fields['customint3']      = $this->get_config('sendmailtoteacher');
        $fields['customint4']      = $this->get_config('sendmailtomanager');

        return $fields;
    }

    function confirmEnrolment($enrols){
        global $DB;
        foreach ($enrols as $enrol){
            // $userenrolment = $DB->get_record('user_enrolments', array('id' => $enrol), '*', MUST_EXIST);
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

            $this->update_user_enrol($instance, $userenrolment->userid, ENROL_USER_ACTIVE);

            $info = $this->getRelatedInfo($enrol);
            $DB->delete_records('enrol_apply_applicationinfo', array('userenrolmentid' => $enrol));
            $this->sendConfirmMail($info);
        }
    }

    function waitEnrolment($enrols){
        global $DB;
        foreach ($enrols as $enrol){
            $userenrolment = $DB->get_record('user_enrolments', array('id' => $enrol, 'status' => ENROL_USER_SUSPENDED), '*', IGNORE_MISSING);

            if ($userenrolment != null) {
                $instance = $DB->get_record('enrol', array('id' => $userenrolment->enrolid, 'enrol' => 'apply'), '*', MUST_EXIST);

                // Check privileges.
                $context = context_course::instance($instance->courseid, MUST_EXIST);
                if (!has_capability('enrol/apply:manageapplications', $context)) {
                    continue;
                }

                $this->update_user_enrol($instance, $userenrolment->userid, ENROL_APPLY_USER_WAIT);

                $info = $this->getRelatedInfo($enrol);
                $this->sendWaitMail($info);
            }
        }
    }

    function cancelEnrolment($enrols){
        global $DB;
        foreach ($enrols as $enrol){
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

            $info = $this->getRelatedInfo($enrol);
            $this->unenrol_user($instance, $userenrolment->userid);
            $DB->delete_records('enrol_apply_applicationinfo', array('userenrolmentid' => $enrol));
            $this->sendCancelMail($info);
        }
    }

    function sendCancelMail($info){
        global $DB;
        global $CFG;

        $replace = array('firstname'=>$info->firstname,'content'=>format_string($info->coursename),'lastname'=>$info->lastname,'username'=>$info->username);
        $body = get_config('enrol_apply', 'cancelmailcontent');
        $body = $this->updateMailContent($body,$replace);
        $contact = core_user::get_support_user();
        email_to_user($info, $contact, get_config('enrol_apply', 'cancelmailsubject'), html_to_text($body), $body);
    }

    function sendConfirmMail($info){
        global $DB;
        global $CFG;

        $replace = array('firstname'=>$info->firstname,'content'=>format_string($info->coursename),'lastname'=>$info->lastname,'username'=>$info->username);
        $body = get_config('enrol_apply', 'confirmmailcontent');
        $body = $this->updateMailContent($body,$replace);
        $contact = core_user::get_support_user();
        email_to_user($info, $contact, get_config('enrol_apply', 'confirmmailsubject'), html_to_text($body), $body);
    }

    function sendWaitMail($info){
        global $DB;
        global $CFG;
        //global $USER;

        $replace = array('firstname'=>$info->firstname,'content'=>format_string($info->coursename),'lastname'=>$info->lastname,'username'=>$info->username);
        $body = get_config('enrol_apply', 'waitmailcontent');
        $body = $this->updateMailContent($body,$replace);
        $contact = get_admin();
        //confirm mail will sent by the admin
        //$contact = $USER;
        email_to_user($info, $contact, get_config('enrol_apply', 'waitmailsubject'), html_to_text($body), $body);
    }

    function sendConfirmMailToTeachers($instance,$info,$applydescription){
        global $DB;
        global $CFG;
        global $USER;

        $courseid = $instance->courseid;
        $instanceid = $instance->id;

        if($instance->customint3 == 1){
            $course = get_course($courseid);
            $context =  context_course::instance($courseid, MUST_EXIST);
            $teacherType = $DB->get_record('role',array("shortname"=>"editingteacher"));
            $teachers = $DB->get_records('role_assignments', array('contextid'=>$context->id,'roleid'=>$teacherType->id));

            if (!$instance->customint1) {
                $info = null;
            }

            $extra = null;
            if($instance->customint2){
                require_once($CFG->dirroot.'/user/profile/lib.php');
                $user = $DB->get_record('user',array('id'=>$USER->id));
                profile_load_custom_fields($user);
                $extra = $user->profile;
            }

            $manageurl = new moodle_url("/enrol/apply/manage.php", array('id'=>$instanceid));

            global $PAGE;
            $renderer = $PAGE->get_renderer('enrol_apply');
            $body = $renderer->application_notification_mail_body($course, $USER, $manageurl, $applydescription, $info, $extra);

            $contact = core_user::get_support_user();

            foreach($teachers as $teacher){
                $editTeacher = $DB->get_record('user',array('id'=>$teacher->userid));

                $info = $editTeacher;
                $info->coursename = $course->fullname;
                email_to_user($info, $contact, get_string('mailtoteacher_suject', 'enrol_apply'), html_to_text($body), $body);
            }
        }
    }

    function sendConfirmMailToManagers($instance,$info,$applydescription){
        global $DB;
        global $CFG;
        global $USER;

        $courseid = $instance->courseid;

        if(get_config('enrol_apply', 'sendmailtomanager') == 1){
            $course = get_course($courseid);
            $context = context_system::instance();
            $managerType = $DB->get_record('role',array("shortname"=>"manager"));
            $managers = $DB->get_records('role_assignments', array('contextid'=>$context->id,'roleid'=>$managerType->id));

            if (!$instance->customint1) {
                $info = null;
            }

            $extra = null;
            if($instance->customint2){
                require_once($CFG->dirroot.'/user/profile/lib.php');
                $user = $DB->get_record('user',array('id'=>$USER->id));
                profile_load_custom_fields($user);
                $extra = $user->profile;
            }

            $manageurl = new moodle_url('/enrol/apply/manage.php');

            global $PAGE;
            $renderer = $PAGE->get_renderer('enrol_apply');
            $body = $renderer->application_notification_mail_body($course, $USER, $manageurl, $applydescription, $info, $extra);

            $contact = core_user::get_support_user();

            foreach($managers as $manager){
                $userWithManagerRole = $DB->get_record('user',array('id'=>$manager->userid));

                $info = $userWithManagerRole;
                $info->coursename = $course->fullname;
                email_to_user($info, $contact, get_string('mailtoteacher_suject', 'enrol_apply'), html_to_text($body), $body);
            }
        }
    }

    function getRelatedInfo($enrolid){
        global $DB;
        global $CFG;
        return $DB->get_record_sql('select u.*,c.fullname as coursename from '.$CFG->prefix.'user_enrolments as ue left join '.$CFG->prefix.'user as u on ue.userid=u.id left join '.$CFG->prefix.'enrol as e on ue.enrolid=e.id left
        join '.$CFG->prefix.'course as c on e.courseid=c.id where ue.id='.$enrolid);
    }

    function updateMailContent($content,$replace){
        foreach ($replace as $key=>$val) {
            $content = str_replace("{".$key."}",$val,$content);
        }
        return $content;
    }
}

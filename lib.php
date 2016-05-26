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
                sendConfirmMailToTeachers($instance, $data, $applydescription);
                sendConfirmMailToManagers($instance, $data, $applydescription);
                
                // Deprecated fixed by Shiro <gigashiro@gmail.com>
                //add_to_log($instance->courseid, 'course', 'enrol', '../enrol/users.php?id='.$instance->courseid, $instance->courseid); //there should be userid somewhere!
                $context = context_course::instance($instance->courseid);
                \core\event\user_enrolment_created::create(
                    array(
                        'objectid' => $instance->id,
                        'courseid' => $instance->courseid, 
                        'context' => $context, 
                        'relateduserid' => $USER->id,
                        'other' => array('enrol' => 'apply')
                    ))->trigger();

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

        if (has_capability('enrol/apply:manage', $context)) {
            $managelink = new moodle_url("/enrol/apply/manage.php", array('id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($managelink, new pix_icon('i/users', get_string('confirmenrol', 'enrol_apply'), 'core', array('class'=>'iconsmall')));
        }

        if (has_capability("enrol/apply:enrol", $context)) {
            $enrollink = new moodle_url("/enrol/apply/enroluser.php", array('enrolid'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($enrollink, new pix_icon('t/enrolusers', get_string('enrolusers', 'enrol_apply'), 'core', array('class'=>'iconsmall')));
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
        if ($this->allow_unenrol($instance) && has_capability("enrol/apply:unenrol", $context)) {
            $url = new moodle_url('/enrol/apply/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array('class'=>'unenrollink', 'rel'=>$ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/apply:manage", $context)) {
            $url = new moodle_url('/enrol/apply/editenrolment.php', $params);
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
        $fields['customint3']      = $this->get_config('sendmailtoteacher');
        $fields['customint4']      = $this->get_config('sendmailtomanager');

        return $fields;
    }
}

function confirmEnrolment($enrols){
    global $DB;
    global $CFG;
    foreach ($enrols as $enrol){
        @$enroluser->id = $enrol;
        @$enroluser->status = 0;

        if($DB->update_record('user_enrolments',$enroluser)){
            $userenrolments = $DB->get_record_sql('select * from '.$CFG->prefix.'user_enrolments where id='.$enrol);
            $role = $DB->get_record_sql("select * from ".$CFG->prefix."role where archetype='student' limit 1");
            @$roleAssignments->userid = $userenrolments->userid;
            @$roleAssignments->roleid = $role->id;
            @$roleAssignments->contextid = 3;
            @$roleAssignments->timemodified = time();
            @$roleAssignments->modifierid = 2;
            $DB->insert_record('role_assignments',$roleAssignments);
            $info = getRelatedInfo($enrol);
            $DB->delete_records('enrol_apply_applicationinfo', array('userenrolmentid' => $enrol));
            sendConfirmMail($info);
        }
    }
}

function waitEnrolment($enrols){
    global $DB;
    global $CFG;
    foreach ($enrols as $enrol){
        @$enroluser->id = $enrol;
        @$enroluser->status = 2;

        if($DB->update_record('user_enrolments',$enroluser)){
            $info = getRelatedInfo($enrol);
            sendWaitMail($info);
        }
    }
}

function cancelEnrolment($enrols){
    global $DB;
    foreach ($enrols as $enrol){
        $info = getRelatedInfo($enrol);
        if($DB->delete_records('user_enrolments',array('id'=>$enrol))){
            $DB->delete_records('enrol_apply_applicationinfo', array('userenrolmentid' => $enrol));
            sendCancelMail($info);
        }
    }
}

function sendCancelMail($info){
    global $DB;
    global $CFG;

    $replace = array('firstname'=>$info->firstname,'content'=>format_string($info->coursename),'lastname'=>$info->lastname,'username'=>$info->username);
    $body = get_config('enrol_apply', 'cancelmailcontent');
    $body = updateMailContent($body,$replace);
    $contact = core_user::get_support_user();
    email_to_user($info, $contact, get_config('enrol_apply', 'cancelmailsubject'), html_to_text($body), $body);
}

function sendConfirmMail($info){
    global $DB;
    global $CFG;

    $replace = array('firstname'=>$info->firstname,'content'=>format_string($info->coursename),'lastname'=>$info->lastname,'username'=>$info->username);
    $body = get_config('enrol_apply', 'confirmmailcontent');
    $body = updateMailContent($body,$replace);
    $contact = core_user::get_support_user();
    email_to_user($info, $contact, get_config('enrol_apply', 'confirmmailsubject'), html_to_text($body), $body);
}

function sendWaitMail($info){
    global $DB;
    global $CFG;
    //global $USER;

    $replace = array('firstname'=>$info->firstname,'content'=>format_string($info->coursename),'lastname'=>$info->lastname,'username'=>$info->username);
    $body = get_config('enrol_apply', 'waitmailcontent');
    $body = updateMailContent($body,$replace);
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
        foreach($teachers as $teacher){
            $editTeacher = $DB->get_record('user',array('id'=>$teacher->userid));
            $body = '<p>'. get_string('coursename', 'enrol_apply') .': '.format_string($course->fullname).'</p>';
            $body .= '<p>'. get_string('applyuser', 'enrol_apply') .': '.$USER->firstname.' '.$USER->lastname.'</p>';
            $body .= '<p>'. get_string('comment', 'enrol_apply') .': '.$applydescription.'</p>';

            if($instance->customint1){
                $body .= '<p><strong>'. get_string('user_profile', 'enrol_apply').'</strong></p>';
                $body .= '<p>'. get_string('firstname') .': '.$info->firstname.'</p>';
                $body .= '<p>'. get_string('lastname') .': '.$info->lastname.'</p>';
                $body .= '<p>'. get_string('email') .': '.$info->email.'</p>';
                $body .= '<p>'. get_string('city') .': '.$info->city.'</p>';
                $body .= '<p>'. get_string('country') .': '.$info->country.'</p>';
                $body .= '<p>'. get_string('preferredlanguage') .': '.$info->lang.'</p>';
                $body .= '<p>'. get_string('description') .': '.$info->description_editor['text'].'</p>';

                $body .= '<p>'. get_string('firstnamephonetic') .': '.$info->firstnamephonetic.'</p>';
                $body .= '<p>'. get_string('lastnamephonetic') .': '.$info->lastnamephonetic.'</p>';
                $body .= '<p>'. get_string('middlename') .': '.$info->middlename.'</p>';
                $body .= '<p>'. get_string('alternatename') .': '.$info->alternatename.'</p>';
                $body .= '<p>'. get_string('url') .': '.$info->url.'</p>';
                $body .= '<p>'. get_string('icqnumber') .': '.$info->icq.'</p>';
                $body .= '<p>'. get_string('skypeid') .': '.$info->skype.'</p>';
                $body .= '<p>'. get_string('aimid') .': '.$info->aim.'</p>';
                $body .= '<p>'. get_string('yahooid') .': '.$info->yahoo.'</p>';
                $body .= '<p>'. get_string('msnid') .': '.$info->msn.'</p>';
                $body .= '<p>'. get_string('idnumber') .': '.$info->idnumber.'</p>';
                $body .= '<p>'. get_string('institution') .': '.$info->institution.'</p>';
                $body .= '<p>'. get_string('department') .': '.$info->department.'</p>';
                $body .= '<p>'. get_string('phone') .': '.$info->phone1.'</p>';
                $body .= '<p>'. get_string('phone2') .': '.$info->phone2.'</p>';
                $body .= '<p>'. get_string('address') .': '.$info->address.'</p>';
            }

            if($instance->customint2){
                require_once($CFG->dirroot.'/user/profile/lib.php');
                $user = $DB->get_record('user',array('id'=>$USER->id));
                profile_load_custom_fields($user);
                foreach ($user->profile as $key => $value) {
                    $body .= '<p>'. $key .': '.$value.'</p>';
                }
            }

            $body .= '<p>'. html_writer::link(new moodle_url("/enrol/apply/manage.php", array('id'=>$instanceid)), get_string('applymanage', 'enrol_apply')).'</p>';
            $contact = core_user::get_support_user();
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
        foreach($managers as $manager){
            $userWithManagerRole = $DB->get_record('user',array('id'=>$manager->userid));
            $body = '<p>'. get_string('coursename', 'enrol_apply') .': '.format_string($course->fullname).'</p>';
            $body .= '<p>'. get_string('applyuser', 'enrol_apply') .': '.$USER->firstname.' '.$USER->lastname.'</p>';
            $body .= '<p>'. get_string('comment', 'enrol_apply') .': '.$applydescription.'</p>';
            if($instance->customint1){
                $body .= '<p><strong>'. get_string('user_profile', 'enrol_apply').'</strong></p>';
                $body .= '<p>'. get_string('firstname') .': '.$info->firstname.'</p>';
                $body .= '<p>'. get_string('lastname') .': '.$info->lastname.'</p>';
                $body .= '<p>'. get_string('email') .': '.$info->email.'</p>';
                $body .= '<p>'. get_string('city') .': '.$info->city.'</p>';
                $body .= '<p>'. get_string('country') .': '.$info->country.'</p>';
                $body .= '<p>'. get_string('preferredlanguage') .': '.$info->lang.'</p>';
                $body .= '<p>'. get_string('description') .': '.$info->description_editor['text'].'</p>';

                $body .= '<p>'. get_string('firstnamephonetic') .': '.$info->firstnamephonetic.'</p>';
                $body .= '<p>'. get_string('lastnamephonetic') .': '.$info->lastnamephonetic.'</p>';
                $body .= '<p>'. get_string('middlename') .': '.$info->middlename.'</p>';
                $body .= '<p>'. get_string('alternatename') .': '.$info->alternatename.'</p>';
                $body .= '<p>'. get_string('url') .': '.$info->url.'</p>';
                $body .= '<p>'. get_string('icqnumber') .': '.$info->icq.'</p>';
                $body .= '<p>'. get_string('skypeid') .': '.$info->skype.'</p>';
                $body .= '<p>'. get_string('aimid') .': '.$info->aim.'</p>';
                $body .= '<p>'. get_string('yahooid') .': '.$info->yahoo.'</p>';
                $body .= '<p>'. get_string('msnid') .': '.$info->msn.'</p>';
                $body .= '<p>'. get_string('idnumber') .': '.$info->idnumber.'</p>';
                $body .= '<p>'. get_string('institution') .': '.$info->institution.'</p>';
                $body .= '<p>'. get_string('department') .': '.$info->department.'</p>';
                $body .= '<p>'. get_string('phone') .': '.$info->phone1.'</p>';
                $body .= '<p>'. get_string('phone2') .': '.$info->phone2.'</p>';
                $body .= '<p>'. get_string('address') .': '.$info->address.'</p>';
            }

            if($instance->customint2){
                require_once($CFG->dirroot.'/user/profile/lib.php');
                $user = $DB->get_record('user',array('id'=>$USER->id));
                profile_load_custom_fields($user);
                foreach ($user->profile as $key => $value) {
                    $body .= '<p>'. $key .': '.$value.'</p>';
                }
            }

            $body .= '<p>'. html_writer::link(new moodle_url('/enrol/apply/manage.php'), get_string('applymanage', 'enrol_apply')).'</p>';
            $contact = core_user::get_support_user();
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

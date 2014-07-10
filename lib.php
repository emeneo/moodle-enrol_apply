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
class enrol_apply_plugin extends enrol_plugin {

	/**
	* Add new instance of enrol plugin with default settings.
	* @param object $course
	* @return int id of new instance
	*/
	public function add_default_instance($course) {
		$fields = array(
		    'status'          => $this->get_config('status'),
		    'roleid'          => $this->get_config('roleid', 0)
		);
		return $this->add_instance($course, $fields);
	}

	public function allow_unenrol(stdClass $instance) {
		// users with unenrol cap may unenrol other users manually manually
		return true;
	}

	public function get_newinstance_link($courseid) {
		$context = get_context_instance(CONTEXT_COURSE, $courseid, MUST_EXIST);

		if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/manual:config', $context)) {
			return NULL;
		}
		// multiple instances supported - different roles with different password
		return new moodle_url('/enrol/apply/edit.php', array('courseid'=>$courseid));
	}

	public function enrol_page_hook(stdClass $instance) {
		global $CFG, $OUTPUT, $SESSION, $USER, $DB;

		if (isguestuser()) {
			// can not enrol guest!!
			return null;
		}
		if ($DB->record_exists('user_enrolments', array('userid'=>$USER->id, 'enrolid'=>$instance->id))) {
			//TODO: maybe we should tell them they are already enrolled, but can not access the course
			//return null;
			return $OUTPUT->notification(get_string('notification', 'enrol_apply'));
		}

		if ($instance->enrolstartdate != 0 and $instance->enrolstartdate > time()) {
			//TODO: inform that we can not enrol yet
			return null;
		}

		if ($instance->enrolenddate != 0 and $instance->enrolenddate < time()) {
			//TODO: inform that enrolment is not possible any more
			return null;
		}

		if ($instance->customint3 > 0) {
			// max enrol limit specified
			$count = $DB->count_records('user_enrolments', array('enrolid'=>$instance->id));
			if ($count >= $instance->customint3) {
				// bad luck, no more self enrolments here
				return $OUTPUT->notification(get_string('maxenrolledreached', 'enrol_self'));
			}
		}

		require_once("$CFG->dirroot/enrol/apply/locallib.php");

		$form = new enrol_apply_enrol_form(NULL, $instance);

		$instanceid = optional_param('instance', 0, PARAM_INT);
		if ($instance->id == $instanceid) {
			if ($data = $form->get_data()) {
				$enrol = enrol_get_plugin('self');
				$timestart = time();
				if ($instance->enrolperiod) {
					$timeend = $timestart + $instance->enrolperiod;
				} else {
					$timeend = 0;
				}

				$roleid = $instance->roleid;
				if(!$roleid){
					$role = $DB->get_record_sql("select * from ".$CFG->prefix."role where archetype='student' limit 1");
					$roleid = $role->id;
				}

				$this->enrol_user($instance, $USER->id, $roleid, $timestart, $timeend,1);
				sendConfirmMailToTeachers($instance->courseid,$data->applydescription);
				
				add_to_log($instance->courseid, 'course', 'enrol', '../enrol/users.php?id='.$instance->courseid, $instance->courseid); //there should be userid somewhere!
				redirect("$CFG->wwwroot/course/view.php?id=$instance->courseid");
			}
		}

		ob_start();
		$form->display();
		$output = ob_get_clean();

		return $OUTPUT->box($output);

	}

	public function get_action_icons(stdClass $instance) {
		global $OUTPUT;

		if ($instance->enrol !== 'apply') {
			throw new coding_exception('invalid enrol instance!');
		}
		$context = get_context_instance(CONTEXT_COURSE, $instance->courseid);

		$icons = array();

		if (has_capability('enrol/manual:config', $context)) {
            $editlink = new moodle_url("/enrol/apply/edit.php", array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit'), 'core', array('class' => 'iconsmall')));
        }

		if (has_capability('enrol/manual:manage', $context)) {
			$managelink = new moodle_url("/enrol/apply/apply.php", array('id'=>$_GET['id'],'enrolid'=>$instance->id));
			$icons[] = $OUTPUT->action_icon($managelink, new pix_icon('i/users', get_string('confirmenrol', 'enrol_apply'), 'core', array('class'=>'iconsmall')));
		}

		if (has_capability("enrol/manual:enrol", $context)) {
			$enrollink = new moodle_url("/enrol/apply/enroluser.php", array('enrolid'=>$instance->id));
			$icons[] = $OUTPUT->action_icon($enrollink, new pix_icon('t/enrolusers', get_string('enrolusers', 'enrol_apply'), 'core', array('class'=>'iconsmall')));
		}
		
		return $icons;
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
}

function getAllEnrolment($id = null){
	global $DB;
	global $CFG;
	if($id){
		$userenrolments = $DB->get_records_sql('select ue.userid,ue.id,u.firstname,u.lastname,u.email,u.picture,c.fullname as course,ue.timecreated from '.$CFG->prefix.'user_enrolments as ue left join '.$CFG->prefix.'user as u on ue.userid=u.id left join '.$CFG->prefix.'enrol as e on ue.enrolid=e.id left join '.$CFG->prefix.'course as c on e.courseid=c.id where ue.status=1 and e.courseid='.$id);
	}else{
		$userenrolments = $DB->get_records_sql('select ue.userid,ue.id,u.firstname,u.lastname,u.email,u.picture,c.fullname as course,ue.timecreated from '.$CFG->prefix.'user_enrolments as ue left join '.$CFG->prefix.'user as u on ue.userid=u.id left join '.$CFG->prefix.'enrol as e on ue.enrolid=e.id left join '.$CFG->prefix.'course as c on e.courseid=c.id where ue.status=1');
	}
	return $userenrolments;
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
			sendConfirmMail($info);
		}
	}
}

function cancelEnrolment($enrols){
	global $DB;
	foreach ($enrols as $enrol){
		$info = getRelatedInfo($enrol);
		if($DB->delete_records('user_enrolments',array('id'=>$enrol))){
			sendCancelMail($info);
		}
	}
}

function sendCancelMail($info){
	global $DB;
	global $CFG;
	$apply_setting = $DB->get_records_sql("select name,value from ".$CFG->prefix."config_plugins where plugin='enrol_apply'");

	$replace = array('firstname'=>$info->firstname,'content'=>$info->coursename);
	$body = $apply_setting['cancelmailcontent']->value;
	$body = updateMailContent($body,$replace);
	$contact = get_admin();
	email_to_user($info, $contact, $apply_setting['cancelmailsubject']->value, '', $body);
}

function sendConfirmMail($info){
	global $DB;
	global $CFG;
	$apply_setting = $DB->get_records_sql("select name,value from ".$CFG->prefix."config_plugins where plugin='enrol_apply'");

	$replace = array('firstname'=>$info->firstname,'content'=>$info->coursename);
	$body = $apply_setting['confirmmailcontent']->value;
	$body = updateMailContent($body,$replace);
	$contact = get_admin();
	email_to_user($info, $contact, $apply_setting['confirmmailsubject']->value, '', $body);
}

function sendConfirmMailToTeachers($courseid,$desc){
	global $DB;
	global $CFG;
	global $USER;
	$apply_setting = $DB->get_records_sql("select name,value from ".$CFG->prefix."config_plugins where plugin='enrol_apply'");
	
	if($apply_setting['sendmailtoteacher']->value == 1){
		$course = $DB->get_record('course',array('id'=>$courseid));
		$context = get_context_instance(CONTEXT_COURSE, $courseid, MUST_EXIST);
		$teacherType = $DB->get_record('role',array("shortname"=>"editingteacher"));
		$teachers = $DB->get_records('role_assignments', array('contextid'=>$context->id,'roleid'=>$teacherType->id));
		foreach($teachers as $teacher){
			$editTeacher = $DB->get_record('user',array('id'=>$teacher->userid));
			$body = '<p>Course: '.$course->fullname.'</p><p>First name: '.$USER->firstname.'</p><p>Last name: '.$USER->lastname.'</p><p>Information: '.$desc.'</p>';
			$contact = get_admin();
			$info = $editTeacher;
			$info->coursename = $course->fullname;
			email_to_user($info, $contact, get_string('mailtoteacher_suject', 'enrol_apply'), '', $body);
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

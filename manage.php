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

require_once('../../config.php');
require_once($CFG->dirroot.'/enrol/apply/lib.php');
require_once($CFG->dirroot.'/enrol/apply/manage_table.php');
require_once($CFG->dirroot.'/enrol/apply/renderer.php');

$id = optional_param('id', null, PARAM_INT);
$userenrol = optional_param('userenrol', null, PARAM_INT);
$formaction = optional_param('formaction', null, PARAM_TEXT);
$userenrolments = optional_param_array('userenrolments', null, PARAM_INT);

require_login();

$manageurlparams = array();
if($id) {
    $instance = $DB->get_record('enrol', array('id' => $id, 'enrol' => 'apply'), '*', MUST_EXIST);
    require_course_login($instance->courseid);
    $course = get_course($instance->courseid);
    $context = context_course::instance($course->id, MUST_EXIST);
    require_capability('enrol/apply:manageapplications', $context);
    $manageurlparams['id'] = $instance->id;
    $pageheading = $course->fullname;
}elseif(is_int($userenrol)&&$userenrol>0){
    $instance = $DB->get_record_sql("SELECT ue.userid,ue.status from {user_enrolments} ue
                        JOIN {enrol} e ON e.id = ue.enrolid
                        where enrol='apply' and ue.id ={$userenrol}");
    if($instance){
        $user = $DB->get_record("user",array("id"=>$instance->userid));
        $contexti = $DB->get_record("context",array("instanceid"=>$instance->userid,"contextlevel"=>CONTEXT_USER));
        $context = context::instance_by_id($contexti->id);
        require_capability('enrol/apply:manageapplications', context::instance_by_id($context->id));
        $manageurlparams['userenrol'] = $userenrol;
        $pageheading = $user->fisrtname." ".$user->lastname;
    }
}else{
    //check if he is a choort
    $sql = "SELECT distinct mc.userid FROM {cohort_members} mc
                WHERE mc.userid <>{$USER->id} and mc.cohortid 
                in (SELECT cohortid FROM {cohort_members} cm WHERE cm.userid ={$USER->id})";
    $cohortsusers = $DB->get_records_sql($sql);
    
    $useradm = array();
    
    if($cohortsusers){
        foreach($cohortsusers as $userchort){
            $contexti = $DB->get_record("context",array("instanceid"=>$userchort->userid,"contextlevel"=>CONTEXT_USER));
            if(has_capability('enrol/apply:manageapplications', context::instance_by_id($contexti->id))){
                $useradm[] = $userchort->userid;
            }
        }
    }
    
    
    $context = context_system::instance();
    if(count($useradm)==0){
        require_capability('enrol/apply:manageapplications', $context);
    }
    $pageheading = get_string('confirmusers', 'enrol_apply');
    $instance = null;
}

$manageurl = new moodle_url('/enrol/apply/manage.php', $manageurlparams);

$PAGE->set_context($context);
$PAGE->set_url($manageurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading($pageheading);
$PAGE->navbar->add(get_string('confirmusers', 'enrol_apply'));
$PAGE->set_title(get_string('confirmusers', 'enrol_apply'));
$PAGE->requires->css('/enrol/apply/style.css');

if ($formaction != null && $userenrolments != null) {
    $enrolapply = enrol_get_plugin('apply');
    switch ($formaction) {
        case 'confirm':
            $enrolapply->confirm_enrolment($userenrolments);
            break;
        case 'wait':
            $enrolapply->wait_enrolment($userenrolments);
            break;
        case 'cancel':
            $enrolapply->cancel_enrolment($userenrolments);
            break;
    }
    redirect($manageurl);
}

$table = new enrol_apply_manage_table($id,$userenrol,$useradm);
$table->define_baseurl($manageurl);

$renderer = $PAGE->get_renderer('enrol_apply');
$renderer->manage_page($table, $manageurl, $instance);

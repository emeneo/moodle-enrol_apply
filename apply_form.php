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
/**
 * Apply enrol plugin implementation.
 *
 * @package    enrol
 * @copyright  2010 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class enrol_apply_apply_form extends moodleform {
    protected $instance;

    /**
     * Overriding this function to get unique form id for multiple self enrolments
     *
     * @return string form identifier
     */
    protected function get_form_identifier() {
        $formid = $this->_customdata->id.'_'.get_class($this);
        return $formid;
    }

    public function definition() {
        global $DB;
        $mform = $this->_form;
        $instance = $this->_customdata;
        $this->instance = $instance;
        $plugin = enrol_get_plugin('apply');

        $heading = $plugin->get_instance_name($instance);
        $mform->addElement('header', 'selfheader', $heading);

        $mform->addElement('html', '<p>'.$instance->customtext1.'</p>');
        $mform->addElement('textarea', 'applydescription', get_string('comment', 'enrol_apply'),'cols="80"');

        //user profile
        global $USER,$CFG,$DB;
        require_once($CFG->libdir.'/gdlib.php');
        require_once($CFG->dirroot.'/user/edit_form.php');
        require_once($CFG->dirroot.'/user/editlib.php');
        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');

        $user = $DB->get_record('user',array('id'=>$USER->id));
        $editoroptions = $filemanageroptions = null;

        $apply_setting = $DB->get_records_sql("select name,value from ".$CFG->prefix."config_plugins where plugin='enrol_apply'");

        if($instance->customint1){
            useredit_shared_definition($mform, $editoroptions, $filemanageroptions,$user);
        }

        if($instance->customint2){
            profile_definition($mform, $user->id);
        }

        $profile_default_values = $user;
        if (is_object($profile_default_values)) {
            $profile_default_values = (array)$profile_default_values;
        }
        $mform->setDefaults($profile_default_values);
        
        $this->add_action_buttons(false, get_string('enrolme', 'enrol_self'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $instance->courseid);

        $mform->addElement('hidden', 'instance');
        $mform->setType('instance', PARAM_INT);
        $mform->setDefault('instance', $instance->id);

        //$mform->addElement('html',"<script type='text/javascript' src='../../lib/jquery/jquery-1.10.2.min.js'></script>");
        //$mform->addElement('html','<script>$(document).ready(function(){$(".collapsible-actions a").trigger("click");})</script>');
        //$mform->addElement('html','<script type="text/javascript">$(document).ready(function(){setTimeout(function(){$(".collapseexpand").trigger("click");},3000)})</script>');
    }

    public function validation($data, $files) {
        global $DB, $CFG;

        $errors = parent::validation($data, $files);
        $instance = $this->instance;

        return $errors;
    }
}
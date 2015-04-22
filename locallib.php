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
/**
 * Self enrol plugin implementation.
 *
 * @package    enrol
 * @subpackage self
 * @copyright  2010 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class enrol_apply_enrol_form extends moodleform {
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
        $mform = $this->_form;
        $instance = $this->_customdata;
        $this->instance = $instance;
        $plugin = enrol_get_plugin('self');

        $heading = $plugin->get_instance_name($instance);
        $mform->addElement('header', 'selfheader', $heading);

        if ($instance->password) {
            $heading = $plugin->get_instance_name($instance);
            $mform->addElement('header', 'selfheader', $heading);
            //change the id of self enrolment key input as there can be multiple self enrolment methods
            $mform->addElement('passwordunmask', 'enrolpassword', get_string('password', 'enrol_self'),
                    array('id' => $instance->id."_enrolpassword"));
        } else {
            // nothing?
        }

		$mform->addElement('html', '<p>'.$instance->customtext1.'</p>');
        $mform->addElement('textarea', 'applydescription', get_string('comment', 'enrol_apply'),'cols="80"');

        //user profile
        global $USER,$CFG;
        require_once($CFG->libdir.'/gdlib.php');
        require_once($CFG->dirroot.'/user/edit_form.php');
        require_once($CFG->dirroot.'/user/editlib.php');
        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');

        $user = $USER;
        $personalcontext = context_user::instance($user->id);
        //profile_load_data($user);

        $mform->addElement('header', 'selfheader', 'User profile');
        //useredit_load_preferences($user, false);

        $strrequired = get_string('required');
        foreach (useredit_get_required_name_fields() as $fullname) {
            $mform->addElement('text', $fullname,  get_string($fullname),  'maxlength="100" size="30"');
            $mform->addRule($fullname, $strrequired, 'required', null, 'client');
            $mform->setType($fullname, PARAM_NOTAGS);
        }
        $enabledusernamefields = useredit_get_enabled_name_fields();
        foreach ($enabledusernamefields as $addname) {
            $mform->addElement('text', $addname,  get_string($addname), 'maxlength="100" size="30"');
            $mform->setType($addname, PARAM_NOTAGS);
        }

        //Do not show email field if change confirmation is pending
        if (!empty($CFG->emailchangeconfirmation) and !empty($user->preference_newemail)) {
            $notice = get_string('emailchangepending', 'auth', $user);
            $notice .= '<br /><a href="edit.php?cancelemailchange=1&amp;id='.$user->id.'">'
                    . get_string('emailchangecancel', 'auth') . '</a>';
            $mform->addElement('static', 'emailpending', get_string('email'), $notice);
        } else {
            $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="30"');
            $mform->addRule('email', $strrequired, 'required', null, 'client');
            $mform->setType('email', PARAM_EMAIL);
        }

        $choices = array();
        $choices['0'] = get_string('emaildisplayno');
        $choices['1'] = get_string('emaildisplayyes');
        $choices['2'] = get_string('emaildisplaycourse');
        $mform->addElement('select', 'maildisplay', get_string('emaildisplay'), $choices);
        $mform->setDefault('maildisplay', 2);

        $choices = array();
        $choices['0'] = get_string('textformat');
        $choices['1'] = get_string('htmlformat');
        $mform->addElement('select', 'mailformat', get_string('emailformat'), $choices);
        $mform->setDefault('mailformat', 1);

        if (!empty($CFG->allowusermailcharset)) {
            $choices = array();
            $charsets = get_list_of_charsets();
            if (!empty($CFG->sitemailcharset)) {
                $choices['0'] = get_string('site').' ('.$CFG->sitemailcharset.')';
            } else {
                $choices['0'] = get_string('site').' (UTF-8)';
            }
            $choices = array_merge($choices, $charsets);
            $mform->addElement('select', 'preference_mailcharset', get_string('emailcharset'), $choices);
        }

        $choices = array();
        $choices['0'] = get_string('emaildigestoff');
        $choices['1'] = get_string('emaildigestcomplete');
        $choices['2'] = get_string('emaildigestsubjects');
        $mform->addElement('select', 'maildigest', get_string('emaildigest'), $choices);
        $mform->setDefault('maildigest', 0);
        $mform->addHelpButton('maildigest', 'emaildigest');

        $choices = array();
        $choices['1'] = get_string('autosubscribeyes');
        $choices['0'] = get_string('autosubscribeno');
        $mform->addElement('select', 'autosubscribe', get_string('autosubscribe'), $choices);
        $mform->setDefault('autosubscribe', 1);

        if (!empty($CFG->forum_trackreadposts)) {
            $choices = array();
            $choices['0'] = get_string('trackforumsno');
            $choices['1'] = get_string('trackforumsyes');
            $mform->addElement('select', 'trackforums', get_string('trackforums'), $choices);
            $mform->setDefault('trackforums', 0);
        }

        $editors = editors_get_enabled();
        if (count($editors) > 1) {
            $choices = array('' => get_string('defaulteditor'));
            $firsteditor = '';
            foreach (array_keys($editors) as $editor) {
                if (!$firsteditor) {
                    $firsteditor = $editor;
                }
                $choices[$editor] = get_string('pluginname', 'editor_' . $editor);
            }
            $mform->addElement('select', 'preference_htmleditor', get_string('textediting'), $choices);
            $mform->setDefault('preference_htmleditor', '');
        } else {
            // Empty string means use the first chosen text editor.
            $mform->addElement('hidden', 'preference_htmleditor');
            $mform->setDefault('preference_htmleditor', '');
            $mform->setType('preference_htmleditor', PARAM_PLUGIN);
        }

        $mform->addElement('text', 'city', get_string('city'), 'maxlength="120" size="21"');
        $mform->setType('city', PARAM_TEXT);
        if (!empty($CFG->defaultcity)) {
            $mform->setDefault('city', $CFG->defaultcity);
        }

        $choices = get_string_manager()->get_list_of_countries();
        $choices= array(''=>get_string('selectacountry').'...') + $choices;
        $mform->addElement('select', 'country', get_string('selectacountry'), $choices);
        if (!empty($CFG->country)) {
            $mform->setDefault('country', $CFG->country);
        }

        $choices = get_list_of_timezones();
        $choices['99'] = get_string('serverlocaltime');
        if ($CFG->forcetimezone != 99) {
            $mform->addElement('static', 'forcedtimezone', get_string('timezone'), $choices[$CFG->forcetimezone]);
        } else {
            $mform->addElement('select', 'timezone', get_string('timezone'), $choices);
            $mform->setDefault('timezone', '99');
        }

        $mform->addElement('select', 'lang', get_string('preferredlanguage'), get_string_manager()->get_list_of_translations());
        $mform->setDefault('lang', $CFG->lang);

        // Multi-Calendar Support - see MDL-18375.
        $calendartypes = \core_calendar\type_factory::get_list_of_calendar_types();
        // We do not want to show this option unless there is more than one calendar type to display.
        if (count($calendartypes) > 1) {
            $mform->addElement('select', 'calendartype', get_string('preferredcalendar', 'calendar'), $calendartypes);
        }

        if (!empty($CFG->allowuserthemes)) {
            $choices = array();
            $choices[''] = get_string('default');
            $themes = get_list_of_themes();
            foreach ($themes as $key=>$theme) {
                if (empty($theme->hidefromselector)) {
                    $choices[$key] = get_string('pluginname', 'theme_'.$theme->name);
                }
            }
            $mform->addElement('select', 'theme', get_string('preferredtheme'), $choices);
        }

        $mform->addElement('editor', 'description_editor', get_string('userdescription'), null, null);
        $mform->setType('description_editor', PARAM_CLEANHTML);

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
    }

    public function validation($data, $files) {
        global $DB, $CFG;

        $errors = parent::validation($data, $files);
        $instance = $this->instance;

        if ($instance->password) {
            if ($data['enrolpassword'] !== $instance->password) {
                if ($instance->customint1) {
                    $groups = $DB->get_records('groups', array('courseid'=>$instance->courseid), 'id ASC', 'id, enrolmentkey');
                    $found = false;
                    foreach ($groups as $group) {
                        if (empty($group->enrolmentkey)) {
                            continue;
                        }
                        if ($group->enrolmentkey === $data['enrolpassword']) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        // we can not hint because there are probably multiple passwords
                        $errors['enrolpassword'] = get_string('passwordinvalid', 'enrol_self');
                    }

                } else {
                    $plugin = enrol_get_plugin('self');
                    if ($plugin->get_config('showhint')) {
                        $textlib = textlib_get_instance();
                        $hint = $textlib->substr($instance->password, 0, 1);
                        $errors['enrolpassword'] = get_string('passwordinvalidhint', 'enrol_self', $hint);
                    } else {
                        $errors['enrolpassword'] = get_string('passwordinvalid', 'enrol_self');
                    }
                }
            }
        }

        return $errors;
    }
}
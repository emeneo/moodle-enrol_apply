<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/enrol/apply/lib.php');

/**
 * Provides the information to restore test enrol instances
 */
class restore_enrol_apply_plugin extends restore_enrol_plugin {

    public function define_enrol_plugin_structure() {
        return array(
                new restore_path_element('applymap', $this->get_pathfor('/applymaps/applymap')),
        );
    }

    /**
     * Process the termmap element
     */
    public function process_applymap($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $enrolid = $this->get_new_parentid('enrol');

        if (!$enrolid) {
            return; // Enrol instance was not restored
        }
        $type = $DB->get_field('enrol', 'enrol', array('id'=>$enrolid));
        if ($type !== 'apply') {
            return; // Enrol was likely converted to manual
        }
        $data->enrolid = $enrolid;
        $data->courseid = $this->task->get_courseid();
        $newitemid = $DB->insert_record('enrol_apply_applicationinfo', $data);
    }

}

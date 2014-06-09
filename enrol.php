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
class enrolment_plugin_apply {
    /**
     * Prints out the configuration form for this plugin. All we need
     * to provide is the form fields. The <form> tags and submit button will
     * be provided for us by Moodle.
     *
     * @param object $formdata Equal to the global $CFG variable, or if
     *      process_config() returned false, the form contents
     * @return void
     */
    public function config_form( $formdata ){
        return;
    }
 
    /**
     * Process the data from the configuration form.
     *
     * @param object $formdata
     * @return boolean True if configuration was successful, False if the user
     *      should be kicked back to config_form() again.
     */
    public function process_config( $formdata ){
        return true;
    }
}
?>
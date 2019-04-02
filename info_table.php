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
 * @copyright  emeneo (http://emeneo.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     emeneo (http://emeneo.com/)
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

class enrol_apply_info_table extends table_sql {

    public $is_collapsible = false;

    public function __construct($enrolid = null) {
        parent::__construct('enrol_apply_info_table');

        global $DB;

        $sqlwhere = 'ue.status != 0';
        $sqlparams = array();
        if ($enrolid != null) {
            $sqlwhere .= " AND e.id = :enrolid";
            $sqlparams['enrolid'] = $enrolid;
        } else {
            $sqlwhere .= " AND e.enrol = :enrol";
            $sqlparams['enrol'] = 'apply';
        }

        $this->set_sql(
            'ue.id AS userenrolmentid, ue.userid, ue.status AS enrolstatus, ue.timecreated AS applydate,
            ai.comment AS applycomment, u.*, c.fullname as course',
            "{user_enrolments} AS ue
            LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
            JOIN {user} u ON u.id = ue.userid
            JOIN {enrol} e ON e.id = ue.enrolid
            JOIN {course} c ON c.id = e.courseid",
            $sqlwhere,
            $sqlparams);

        //$this->no_sorting('checkboxcolumn');
    }

    /**
     * Get any extra classes names to add to this row in the HTML.
     * @param $row array the data for this row. Note (Johannes): this is actually an object with all sql columns.
     * @return string added to the class="" attribute of the tr.
     */
    public function get_row_class($row) {
        if ($row->enrolstatus == 2) {
            return 'enrol_apply_waitinglist_highlight';
        }
        return '';
    }
    /*
    public function col_checkboxcolumn($row) {
        return html_writer::checkbox('userenrolments[]', $row->userenrolmentid, false);
    }

    public function col_fullname($row) {
        // The $row variable contains all user fields, see sql query.
        global $OUTPUT;
        $col = $OUTPUT->user_picture($row, array('popup' => true));
        $col .= fullname($row);
        return $col;
    }

    public function col_applydate($row) {
        return date("Y-m-d", $row->applydate);
    }
    */
}

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
 *
 * @package    enrol_apply
 * @copyright  2016 sudile GbR (http://www.sudile.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Johannes Burk <johannes.burk@sudile.com>
 */

defined('MOODLE_INTERNAL') || die();

class enrol_apply_renderer extends plugin_renderer_base {
    public function manage_page($table, $manageurl, $instance) {
        echo $this->header();
        echo $this->heading(get_string('confirmusers', 'enrol_apply'));
        echo get_string('confirmusers_desc', 'enrol_apply');
        $this->manage_form($table, $manageurl, $instance);
        echo $this->footer();
    }

    public function edit_page($mform) {
        echo $this->header();
        echo $this->heading(get_string('pluginname', 'enrol_apply'));
        $mform->display();
        echo $this->footer();
    }

    public function manage_form($table, $manageurl, $instance) {
        echo html_writer::start_tag('form', array(
            'id' => 'enrol_apply_manage_form',
            'method' => 'post',
            'action' => $manageurl->out()));

        $this->manage_table($table, $instance);

        if ($table->totalrows > 0) {
            echo html_writer::empty_tag('br');
            echo html_writer::start_tag('div', array('class' => 'formaction'));

            $formactions = array(
                'confirm' => get_string('btnconfirm', 'enrol_apply'),
                'wait' => get_string('btnwait', 'enrol_apply'),
                'cancel' => get_string('btncancel', 'enrol_apply'));
            echo html_writer::tag('label', get_string('withselectedusers'), array('for' => 'formaction'));
            echo html_writer::select($formactions, 'formaction', '', array('' => 'choosedots'), array('id' => 'formaction'));
            echo html_writer::tag('noscript',
                html_writer::empty_tag('input', array('type' => 'submit', get_string('submit'))),
                array('style' => 'display: inline;'));

            echo html_writer::end_tag('div');

            $this->page->requires->js_call_amd('enrol_apply/manage', 'init');
        }
        echo html_writer::end_tag('form');
    }

    public function info_page($table, $manageurl,$instance) {
        echo $this->header();
        echo $this->heading(get_string('submitted_info', 'enrol_apply'));
        echo get_string('submitted_info', 'enrol_apply');
        $this->info_form($table, $manageurl,$instance);
        echo $this->footer();
    }

    public function manage_table($table, $instance) {
        $columns = array(
            'checkboxcolumn',
            'course',
            'fullname', // Magic happens here: The column heading will automatically be set.
            'email',
            'applydate',
            'applycomment');
        $headers = array(
            html_writer::checkbox('toggleall', 'toggleall', false, '', array('id' => 'toggleall')),
            get_string('course'),
            'fullname', // Magic happens here: The column heading will automatically be set due to column name 'fullname'.
            get_string('email'),
            get_string('applydate', 'enrol_apply'),
            get_string('applycomment', 'enrol_apply'),
        );
        $table->define_columns($columns);
        $table->define_headers($headers);

        $table->sortable(true, 'id');

        $table->out(50, true);
    }

    public function info_form($table, $manageurl,$instance) {
        echo html_writer::start_tag('form', array(
            'id' => 'enrol_apply_info_form',
            'method' => 'post',
            'action' => $manageurl->out()));

        $this->info_table($table,$instance);

        if ($table->totalrows > 0) {
            echo html_writer::empty_tag('br');
            echo html_writer::start_tag('div', array('class' => 'formaction'));


            echo html_writer::end_tag('div');

            $this->page->requires->js_call_amd('enrol_apply/info', 'init');
        }
        echo html_writer::end_tag('form');
    }

    public function info_table($table,$instance) {
        $columns = array(
            'fullname',
            'applycomment');
        $headers = array(
            'User', // Magic happens here: The column heading will automatically be set due to column name 'fullname'.
            $instance->customtext2);
        $table->define_columns($columns);
        $table->define_headers($headers);

        $table->sortable(true, 'id');

        $table->out(50, true);
    }

    public function application_notification_mail_body(
        $course, $user, $manageurl, $applydescription, $standarduserfields = null, $extrauserfields = null) {

        $body = '<p>'. get_string('coursename', 'enrol_apply') .': '.format_string($course->fullname).'</p>';
        $body .= '<p>'. get_string('applyuser', 'enrol_apply') .': '.$user->firstname.' '.$user->lastname.'</p>';
        $body .= '<p>'. get_string('comment', 'enrol_apply') .': '.$applydescription.'</p>';
        if ($standarduserfields) {
            $body .= '<p><strong>'. get_string('user_profile', 'enrol_apply').'</strong></p>';
            $body .= '<p>'. get_string('firstname') .': '.$standarduserfields->firstname.'</p>';
            $body .= '<p>'. get_string('lastname') .': '.$standarduserfields->lastname.'</p>';
            $body .= '<p>'. get_string('email') .': '.$standarduserfields->email.'</p>';
            $body .= '<p>'. get_string('city') .': '.$standarduserfields->city.'</p>';
            $body .= '<p>'. get_string('country') .': '.$standarduserfields->country.'</p>';
            if(isset($standarduserfields->lang)){
                $body .= '<p>'. get_string('preferredlanguage') .': '.$standarduserfields->lang.'</p>';
            }
            $body .= '<p>'. get_string('description') .': '.$standarduserfields->description_editor['text'].'</p>';

            $body .= '<p>'. get_string('firstnamephonetic') .': '.$standarduserfields->firstnamephonetic.'</p>';
            $body .= '<p>'. get_string('lastnamephonetic') .': '.$standarduserfields->lastnamephonetic.'</p>';
            $body .= '<p>'. get_string('middlename') .': '.$standarduserfields->middlename.'</p>';
            $body .= '<p>'. get_string('alternatename') .': '.$standarduserfields->alternatename.'</p>';
            $body .= '<p>'. get_string('url') .': '.$standarduserfields->url.'</p>';
            $body .= '<p>'. get_string('icqnumber') .': '.$standarduserfields->icq.'</p>';
            $body .= '<p>'. get_string('skypeid') .': '.$standarduserfields->skype.'</p>';
            $body .= '<p>'. get_string('aimid') .': '.$standarduserfields->aim.'</p>';
            $body .= '<p>'. get_string('yahooid') .': '.$standarduserfields->yahoo.'</p>';
            $body .= '<p>'. get_string('msnid') .': '.$standarduserfields->msn.'</p>';
            $body .= '<p>'. get_string('idnumber') .': '.$standarduserfields->idnumber.'</p>';
            $body .= '<p>'. get_string('institution') .': '.$standarduserfields->institution.'</p>';
            $body .= '<p>'. get_string('department') .': '.$standarduserfields->department.'</p>';
            $body .= '<p>'. get_string('phone') .': '.$standarduserfields->phone1.'</p>';
            $body .= '<p>'. get_string('phone2') .': '.$standarduserfields->phone2.'</p>';
            $body .= '<p>'. get_string('address') .': '.$standarduserfields->address.'</p>';
        }

        if ($extrauserfields) {
            foreach ($extrauserfields as $key => $value) {
                $body .= '<p>'. $key .': '.$value.'</p>';
            }
        }

        $body .= '<p>'. html_writer::link($manageurl, get_string('applymanage', 'enrol_apply')).'</p>';

        return $body;
    }
}

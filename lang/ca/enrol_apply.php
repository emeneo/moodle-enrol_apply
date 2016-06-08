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
 */

// The name of your plugin. Displayed on admin menus.
$string['enrolname'] = 'Inscripció prèvia aprovació';
$string['pluginname'] = 'Inscripció prèvia aprovació';
$string['pluginname_desc'] = 'With this plugin users can apply to a course and a teacher have to comfirm before the user gets enroled.';

$string['confirmmailsubject'] = 'Assumpte del correu de confirmació';
$string['confirmmailcontent'] = 'Contingut del correu de confirmació';
$string['cancelmailsubject'] = 'Assumpte del correu de cancel·lació';
$string['cancelmailcontent'] = 'Contingut del correu de cancel·lació';
$string['confirmmailcontent_desc'] = 'Sisplau, utilitza marques especials que se substituiran en el contingut del correu.<br>{firstname}:Nom registrat per l\'usuari; {content}:Nom del curs';
$string['cancelmailcontent_desc'] = 'Sisplau, utilitza marques especials que se substituiran en el contingut del correu.<br>{firstname}:Nom registrat per l\'usuari; {content}:Nom del curs';

$string['confirmusers'] = 'Confirmar inscripcions';

$string['coursename'] = 'Curs';
$string['applyuser'] = 'Nom / Cognom';
$string['applyusermail'] = 'Correu electrònic';
$string['applydate'] = 'Data d\'inscripció';
$string['btnconfirm'] = 'Confirmar';
$string['btncancel'] = 'Cancel·lar';
$string['enrolusers'] = 'Inscriure usuaris';

$string['status'] = 'Permet inscripció prèvia aprovació';
$string['confirmenrol'] = 'Gestionar sol·licituds';

$string['apply:config'] = 'Configurar instàncies d\'Inscripció prèvia aprovació'; // Needs more insight.
$string['apply:manageapplications'] = 'Gestionar la Inscripció prèvia aprovació'; // Needs more insight.
$string['apply:unenrol'] = 'Cancel·lar usuaris del curs'; // Needs more insight.

$string['notification'] = '<b>Sol·licitud d\'inscripció enviada correctament</b>. <br/><br/>Rebràs una notificació per correu electrònic un cop estigui confirmada la inscripció.';

$string['sendmailtoteacher'] = 'Enviar notificacions per correu als professors';
$string['sendmailtomanager'] = 'Enviar notificacions per correu als administradors';
$string['mailtoteacher_suject'] = 'Nova inscripció al curs!';
$string['editdescription'] = 'Descripció de l\'àrea de text';
$string['comment'] = 'Comentari';
$string['applymanage'] = 'Gestionar inscripcions';

$string['status_desc'] = 'Allow course access of internally enrolled users.';

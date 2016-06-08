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
$string['enrolname'] = 'Matrículas solicitadas';
$string['pluginname'] = 'Matrículas solicitadas';
$string['pluginname_desc'] = 'With this plugin users can apply to a course and a teacher have to comfirm before the user gets enroled.'; // Needs to be translated.

$string['confirmmailsubject'] = 'Assunto do e-mail de confirmação';
$string['confirmmailcontent'] = 'Conteúdo do e-mail de confirmação';
$string['cancelmailsubject'] = 'Assunto do e-mail de cancelamento';
$string['cancelmailcontent'] = 'Conteúdo do e-mail de cancelamento';
$string['confirmmailcontent_desc'] = 'Por favor, use marcas especiais que substituirão o conteúdo do e-mail.<br>{firstname}:Nome registrado pelo usuário; {content}:Nome do curso';
$string['cancelmailcontent_desc'] = 'Por favor, use marcas especiais que substituirão o conteúdo do e-mail.<br>{firstname}:Nome registrado pelo usuário {content}:Nome do curso';

$string['confirmusers'] = 'Confirmar matrículas';

$string['coursename'] = 'Curso';
$string['applyuser'] = 'Nome / Sobrenome';
$string['applyusermail'] = 'E-mail';
$string['applydate'] = 'Data de solicitação'; // Enrol date -> Fecha de matriculación.
$string['btnconfirm'] = 'Confirmar';
$string['btncancel'] = 'Cancelar';
$string['enrolusers'] = 'Matricular usuários';

$string['status'] = 'Aceitar matrícula após aprovação';
$string['confirmenrol'] = 'Gerenciar solicitações';

$string['apply:config'] = 'Configurar instâncias de matrículas solicitadas'; // Needs more insight.
$string['apply:manageapplications'] = 'Gerenciar matrículas solicitadas'; // Needs more insight.
$string['apply:unenrol'] = 'Cancelar usuários do curso'; // Needs more insight.

$string['notification'] = '<b>Solicitação de matrícula enviada com sucesso</b>. <br/><br/>Você será notificado por e-mail quando a sua matrícula for confirmada.';

$string['sendmailtoteacher'] = 'Enviar e-mail de notificação para professores';
$string['mailtoteacher_suject'] = 'Nova solicitação de inscrição!';
$string['editdescription'] = 'Descrição';
$string['applymanage'] = 'Manage enrolment applications'; // Needs to be translated.

$string['status_desc'] = 'Allow course access of internally enrolled users.'; // Needs to be translated.

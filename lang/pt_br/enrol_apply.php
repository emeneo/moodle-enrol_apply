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
// The name of your plugin. Displayed on admin menus.
$string['enrolname'] = 'Matrículas solicitadas';
$string['pluginname'] = 'Matrículas solicitadas';

$string['confirmmailsubject'] = 'Assunto do e-mail de confirmação';
$string['confirmmailcontent'] = 'Conteúdo do e-mail de confirmação';
$string['cancelmailsubject'] = 'Assunto do e-mail de cancelamento';
$string['cancelmailcontent'] = 'Conteúdo do e-mail de cancelamento';
$string['mailaddress'] = 'Endereço do e-mail de envio'; // Those configuration parameters
$string['mailusername'] = 'Usuário do e-mail de envio'; //  are commented out in code
$string['mailpassword'] = 'Senha do e-mail de envio'; // Couldn't make an accurate translation without seen those in action
$string['confirmmailcontent_desc'] = 'Por favor, use marcas especiais que substituirão o conteúdo do e-mail.<br>{firstname}:Nome registrado pelo usuário; {content}:Nome do curso';
$string['cancelmailcontent_desc'] = 'Por favor, use marcas especiais que substituirão o conteúdo do e-mail.<br>{firstname}:Nome registrado pelo usuário {content}:Nome do curso';

$string['confirmusers'] = 'Confirmar matrículas';

$string['coursename'] = 'Curso';
$string['applyuser'] = 'Nome / Sobrenome';
$string['applyusermail'] = 'E-mail';
$string['applydate'] = 'Data de solicitação'; // Enrol date -> Fecha de matriculación
$string['btnconfirm'] = 'Confirmar';
$string['btncancel'] = 'Cancelar';
$string['enrolusers'] = 'Matricular usuários';

$string['status'] = 'Aceitar matrícula após aprovação';
$string['confirmenrol'] = 'Gerenciar solicitações';

$string['apply:config'] = 'Configurar instâncias de matrículas solicitadas'; // Needs more insight
$string['apply:manage'] = 'Gerenciar matrículas solicitadas'; // Needs more insight
$string['apply:unenrol'] = 'Cancelar usuários do curso'; // Needs more insight
$string['apply:unenrolapply'] = 'Cancelar minha matrícula do curso'; // Needs more insight. Very ugly translation!
 
// Description of your plugin. Shown on the plugin's configuration screen.
$string['description'] = '';
$string['notification'] = '<b>Solicitação de matrícula enviada com sucesso</b>. <br/><br/>Você será notificado por e-mail quando a sua matrícula for confirmada.';

$string['sendmailtoteacher'] = 'Enviar e-mail de notificação para professores';
$string['mailtoteacher_suject'] = 'Nova solicitação de inscrição!';
$string['setting_edit'] = 'Editar';
$string['editdescription'] = 'Descrição'; 
?>

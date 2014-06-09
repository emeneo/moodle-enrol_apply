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

$string['confirmmailsubject'] = 'Asunto del correo de confirmación';
$string['confirmmailcontent'] = 'Contenido del correo de confirmación';
$string['cancelmailsubject'] = 'Asunto del correo de cancelación';
$string['cancelmailcontent'] = 'Contenido del correo de cancelación';
$string['mailaddress'] = 'Send mail address'; // Those configuration parameters
$string['mailusername'] = 'Send mail username'; //  are commented out in code
$string['mailpassword'] = 'Send mail password'; // Couldn't make an accurate translation without seen those in action
$string['confirmmailcontent_desc'] = 'Por favor, use marcas especiales que se substituirán en el contenido del correo.<br>{firstname}:Nombre registrado por el usuario; {content}:Nombre del curso';
$string['cancelmailcontent_desc'] = 'Por favor, use marcas especiales que se substituirán en el contenido del correo.<br>{firstname}:Nombre registrado por el usuario; {content}:Nombre del curso';

$string['confirmusers'] = 'Confirmar matrículas';

$string['coursename'] = 'Curso';
$string['applyuser'] = 'Nombre / Apellido';
$string['applyusermail'] = 'Correo electrónico';
$string['applydate'] = 'Fecha de solicitud'; // Enrol date -> Fecha de matriculación
$string['btnconfirm'] = 'Confirmar';
$string['btncancel'] = 'Cancelar';
$string['enrolusers'] = 'Matricular usuarios';

$string['status'] = 'Aceptar matriculación tras aprobación';
$string['confirmenrol'] = 'Gestionar solicitudes';

$string['apply:config'] = 'Configurar instancias de matrículas solicitadas'; // Needs more insight
$string['apply:manage'] = 'Gestionar matrículas solicitadas'; // Needs more insight
$string['apply:unenrol'] = 'Cancelar usuarios del curso'; // Needs more insight
$string['apply:unenrolapply'] = 'Cancelarse a si mismo del curso'; // Needs more insight. Very ugly translation!
 
// Description of your plugin. Shown on the plugin's configuration screen.
$string['description'] = '';
$string['notification'] = '<b>Solicitud de matriculación enviada correctamente</b>. <br/><br/>Será notificado por correo electrónico en cuanto se confirme su matriculación.';
 
?>

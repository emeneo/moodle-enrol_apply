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

$string['apply:config'] = 'Configurar instàncies d\'Inscripció prèvia aprovació'; // Needs more insight
$string['apply:manage'] = 'Gestionar la Inscripció prèvia aprovació'; // Needs more insight
$string['apply:unenrol'] = 'Cancel·lar usuaris del curs'; // Needs more insight
$string['apply:unenrolapply'] = 'Cancel·lar-se a si mateix del curs'; // Needs more insight. Very ugly translation!
 
$string['notification'] = '<b>Sol·licitud d\'inscripció enviada correctament</b>. <br/><br/>Rebràs una notificació per correu electrònic un cop estigui confirmada la inscripció.';

$string['sendmailtoteacher'] = 'Enviar notificacions per correu als professors';
$string['sendmailtomanager'] = 'Enviar notificacions per correu als administradors';
$string['mailtoteacher_suject'] = 'Nova inscripció al curs!';
$string['editdescription'] = 'Descripció de l\'àrea de text';
$string['comment'] = 'Comentari';
$string['applymanage'] = 'Gestionar inscripcions';

$string['status_desc'] = 'Allow course access of internally enrolled users.';

?>
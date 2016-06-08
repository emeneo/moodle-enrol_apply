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
$string['enrolname'] = 'Conferma di iscrizione al corso';
$string['pluginname'] = 'Conferma di iscrizione al corso';
$string['pluginname_desc'] = 'Con questo plugin gli utenti possono far richiesta di iscriversi al corso e verranno inseriti solo previa conferma da parte del docente';

$string['confirmmailsubject'] = 'Oggetto mail di conferma';
$string['confirmmailcontent'] = 'Contenuto mail di conferma';
$string['cancelmailsubject'] = 'Oggetto mail di cancellazione';
$string['cancelmailcontent'] = 'Contenuto mail di cancellazione';
$string['confirmmailcontent_desc'] = 'Potete usare questi marks nella mail.<br>{firstname}:Nome dell\'utente iscritto; {content}:Nome del corso;{lastname}:Cognome dell\'utente;{username}:Nome di registrazione';
$string['cancelmailcontent_desc'] = 'Potete usare questi marks nella mail.<br>{firstname}:Nome dell\'utente iscritto; {content}:Nome del corso;{lastname}:Cognome dell\'utente;{username}:Nome di registrazione';

$string['confirmusers'] = 'Conferma dell\'iscrizione';

$string['coursename'] = 'Corso';
$string['applyuser'] = 'Nome / Cognome';
$string['applyusermail'] = 'Email';
$string['applydate'] = 'Data di iscrizione';
$string['btnconfirm'] = 'Conferma';
$string['btncancel'] = 'Cancella';
$string['enrolusers'] = 'Iscrivi utenti';

$string['status'] = 'Permetti la conferma di iscrizione al corso';
$string['confirmenrol'] = 'Gestisci l\'applicazione';

$string['apply:config'] = 'Configura le istanze del plugin';
$string['apply:manageapplications'] = 'Gestisci le richieste di iscrizione';
$string['apply:unenrol'] = 'Cancella gli utenti dal corso';
$string['apply:unenrolself'] = 'Permetti all\'utente di disiscriversi dal corso';

$string['notification'] = '<b>Richiesta di iscrizione al corso correttamente inviata</b>. <br/><br/>Verrai informato via email appena la tua richiesta di iscrizione viene confermata. Se vuoi iscriverti ad altri corsi, premi sul "catalogo dei corsi" sul top menu.';

$string['sendmailtoteacher'] = 'Manda email di notifica agli insegnanti';
$string['sendmailtomanager'] = 'Manda email di notifica agli amministratori';
$string['mailtoteacher_suject'] = 'Nuova richiesta di iscrizione!';
$string['editdescription'] = 'Descrizione area di testo';
$string['comment'] = 'Commento';
$string['applymanage'] = 'Gestisci richieste di iscrizione';

$string['status_desc'] = 'Permette l\'accesso al corso agli utenti iscritti internamente.';

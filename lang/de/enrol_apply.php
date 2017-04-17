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
$string['enrolname'] = 'Bestätigung der Kurseinschreibung';
$string['pluginname'] = 'Bestätigung der Kurseinschreibung';
$string['pluginname_desc'] = 'With this plugin users can apply to a course and a teacher have to comfirm before the user gets enroled.';

$string['confirmmailsubject'] = 'E-Mail-Betreff für Bestätigung der Einschreibung';
$string['confirmmailcontent'] = 'E-Mail für Bestätigung der Einschreibung';
$string['waitmailsubject'] = 'E-Mail-Betreff für Benachrichtigung über das setzen auf Warteliste';
$string['waitmailcontent'] = 'E-Mail für Benachrichtigung über das setzen auf Warteliste';
$string['cancelmailsubject'] = 'Mail-Betreff für Verwerfen der Einschreibung';
$string['cancelmailcontent'] = 'Mail für Verwerfen der Einschreibung';
$string['confirmmailcontent_desc'] = 'Bitte benutzen Sie die Spezialmarkierungen, um den gewünschten Mailinhalt zu ersetzen.<br>{firstname}:Registrierungsname; {content}:Kursname';
$string['waitmailcontent_desc'] = 'Bitte benutzen Sie die Spezialmarkierungen, um den gewünschten Mailinhalt zu ersetzen.<br>{firstname}:Registrierungsname; {content}:Kursname';
$string['cancelmailcontent_desc'] = 'Bitte benutzen Sie die Spezialmarkierungen, um den gewünschten Mailinhalt zu ersetzen.<br>{firstname}:Registrierungsname; {content}:Kursname';

$string['confirmusers'] = 'Einschreibung bestätigen';
$string['confirmusers_desc'] = 'Nutzer in grau hinterlegten Zeilen befinden sich auf der Warteliste und können noch nachträglich dem Kurs hinzugefügt oder gelöscht werden.';

$string['coursename'] = 'Kurs';
$string['applyuser'] = 'Vorname / Nachname';
$string['applyusermail'] = 'Email';
$string['applydate'] = 'Einschreibungsdatum';
$string['btnconfirm'] = 'Einschreibungsanfragen bestätigen';
$string['btnwait'] = 'markierte Nutzer auf die Warteliste setzen';
$string['btncancel'] = ' Einschreibungsanfragen ablehnen';
$string['enrolusers'] = 'Benutzer manuell einschreiben';

$string['status'] = 'Bestätigung der Kurseinschreibung erlauben';
$string['confirmenrol'] = 'Einschreibeanfragen bearbeiten';

$string['apply:config'] = 'Einschreibungsbelegstellen anlegen';
$string['apply:manageapplications'] = 'Einschreibungsanfragen verwalten';
$string['apply:unenrol'] = 'Benutzer aus dem Kurs entfernen';

$string['notification'] = '<b>Einschreibungsantrag wurde erfolgreich gesendet.</b>. <br/><br/>Sie werden via Mail informiert, sobald Ihre Einschreibung bestätigt wurde.';

$string['sendmailtoteacher'] = 'Sende eine Hinweis-E-Mail an den Trainer';
$string['mailtoteacher_suject'] = 'Neue Anfrage zur Einschreibung';
$string['editdescription'] = 'Beschreibung anpassen';
$string['applymanage'] = 'Manage enrolment applications';

$string['status_desc'] = 'Kurszugriff für intern eingeschriebene Nutzer/innen erlauben.';

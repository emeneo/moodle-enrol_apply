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

$string['expirynotifyall'] = 'Enseignant et utilisateur inscrit';
$string['expirynotifyenroller'] = 'Enseignant uniquement';

$string['group'] = 'Affectation aux groupes';
$string['group_help'] = 'Vous pouvez affecter aucun ou plusieurs groupes';

$string['opt_commentaryzone'] = 'Champ commentaire';
$string['opt_commentaryzone_help'] = 'Oui -> Active le champ commentaire dans le formulaire d\'inscription';

$string['expirymessageenrollersubject'] = 'Notification de fin d\'inscription : Confirmation d\'inscription';
$string['expirymessageenrollerbody'] = 'Confirmation d\'inscription dans le cours \'{$a->course}\' va expirer dans les {$a->threshold} pour les utilisateurs suivants :

    {$a->users}

Pour allonger leurs inscription, allez sur {$a->extendurl}';

$string['expirymessageenrolledsubject'] = 'Notification de fin d\'inscription : Confirmation d\'inscription';
$string['expirymessageenrolledbody'] = '{$a->user},

Ceci est une notification pour vous prévenir que votre inscription dans le cours \'{$a->course}\' va arriver à expiration à {$a->timeend}.

Si vous avez besoin d\'aide, veuillez contacter {$a->enroller}.';


$string['sendexpirynotificationstask'] = 'Tâche d\'envoie des notifications du seuil de notification : Confirmation d\'inscription';

$string['messageprovider:expiry_notification'] = 'Confirmation d\'inscription : Seuil de notification';


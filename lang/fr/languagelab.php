<?php

/**
 * *************************************************************************
 * *             OWLL LANGUAGE LAB Version 3 for Moodle 2                 **
 * *************************************************************************
 * @package     mod                                                       **
 * @subpackage  languagelab                                               **
 * @name        languagelab                                               **
 * @copyright   oohoo.biz                                                 **
 * @link        http://oohoo.biz                                          **
 * @author      Nicolas Bretin                                            **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
$string['name'] = 'Nom';
$string['modulename'] = 'Labo de langues - OWLL';
$string['modulenameplural'] = 'Labos de langues - OWLL';
$string['modulename_help'] = 'OWLL (OOHOO Web-based Language Lab) est un plugiciel Moodle qui reproduit toutes les fonctions d’un laboratoire de langue traditionnel et y inclut des ressources additionnelles rendues possible par le fait qu’il est basé sur le Web.
Il permet un usage synchrone et asynchrone en contexte d’enseignement face à face ou à distance.';
$string['modulename_link'] = 'http://oohoo.biz/index.php/fr/plugiciels/apprentissage-de-la-langue/owll-oohoo-labo-de-langues/';
$string['languagelab'] = 'Labo de langues - OWLL';
$string['pluginadministration'] = 'Labo de langues - OWLL';
$string['pluginname'] = 'Labo de langues - OWLL';
$string['red5server'] = 'URL de votre serveur RTMP';
$string['red5server_help'] = 'Entrez l\'adresse IP ou le URL (FQDN) de votre serveur RTMP. Localhost ne fonctionnera pas! Vous pouvez spécifier à la fin le port si celui-ci est different du port par defaut de red5 (ex : 123.456.789:12345)';
$string['red5serverfolder'] = 'Dossier de votre serveur Red5 (ex : oflaDemo)';
$string['red5serverfolder_help'] = 'Votre premier dossier d\'acces à votre RTMP server. Ce dossier est l\'application qui lit et enregistre les fichiers.';
$string['red5config'] = 'Entrez l\'adresse IP ou le URL (FQDN) de votre serveur Red5. Localhost ne fonctionnera pas!';
$string['name'] = 'Nom de l\'activité';
$string['description'] = 'Description';
$string['availabledate'] = 'Disponible de: ';
$string['duedate'] = 'Date d\'échéance: ';
$string['general'] = 'Général';
$string['attempts'] = 'Un enregistrement par étudiant!';
$string['recording_timelimit'] = 'Le temps d\'enregistrement en minutes. (0 = illimité)';
$string['attempts_warning'] = 'Note: Seule une soumission sera permise.<br>Vous pouvez revenir et enregistrer par-dessus la soumission précédente, ce qui aura pour effet de la supprimer.<br>Seule le dernier enregistement soumis sera évalué';
$string['master_track'] = 'Piste contrôle (mp3 seulement)';
$string['master_track_file'] = 'La piste contrôle est actuellement utilisée';
$string['master_track_help'] = 'De façon similaire, si vous avez des enregistrements préalables en format mp3, vous pouvez décider de les téléverser et les utiliser comme pistes de contrôle.
                                Si vous décidez d\'utiliser le fichier mp3, il est important que vous cochiez la case \'<i>Utiliser le mp3 téléversé comme piste de contrôle</i>\', sinon, la piste enregistrée manuellement sera utilisée.<br>Si vous avez déjà téléversé un fichier mp3 mais aimeriez utiliser l\'enregistrement manuel, désactivez la case \'<i>Utiliser le mp3 téléversé comme piste de contrôle</i>\' ';
$string['not_available'] = 'Cette activité est terminée. Vous pouvez visionner les commentaires de votre enseignant. Vous ne pouvez pas faire un nouvel enregistrement.';
$string['no_due_date'] = 'Aucune date d\'échéance n\'est entrée.';
$string['no_available_date'] = 'Aucune date n\'est entrée.';
$string['submit'] = 'Enregistrer';
$string['recorderdescription'] = 'Enregistreuse';
$string['use_grade_book'] = 'Utiliser le carnet des notes';
$string['emailsubject'] = 'Activité - ';
$string['emailgreeting'] = 'Bonjour';
$string['emailbodynewreply'] = 'J\'ai ajouté un commentaire à votre enregistrement. Prière de retourner à l\'activité et écouter ou lire mes commentaires. ';
$string['emailbodydelete'] = 'Je vous demande de recommencer votre enregistrement. Prière de retourner à l\'activité et recommencer. ';
$string['emailthankyou'] = 'Merci';
$string['prefix'] = 'Entrer un préfixe pour les flux audio et vidéos enregistrés';
$string['prefix_help'] = 'Le préfixe est ici utile si le serveur Red5 est utilisé pour le traitement d\'autres contenus. Il vous sera alors possible d\'identifier aisément les flux audio et vidéos enregistrés. ';
$string['submit_recording'] = 'Soumettre votre enregistrement';
$string['recording_failed_save'] = 'Votre enregistrement n\'a pas été saisi';
$string['recording_saved'] = 'Votre enregistrement a été saisi';
$string['recording_exists'] = 'Note: vous avez déjà un enregistrement. Prière de réviser l\'enregistrement avant de cliquer sur le bouton d\'enregistrement.<br>En cliquant sur le bouton d\'enregistrement, vous effacez l\'enregistrement précédent.';
$string['words'] = 'Liste de mots à enregistrer pour les étudiants ';
$string['adapter_access'] = 'Accès sécuritaire au plugiciel Red5 Adapter?';
$string['adapter_access_help'] = 'Le serveur RAP (plugiciel Red5 Adapter) est-il protégé par un certificat? Ex: https';
$string['adapter_server'] = 'L\'adresse du serveur pour acceder au fichier adapter.';
$string['adapter_server_help'] = 'L\'adresse du serveur pour acceder au fichier adapter. En général, cette valeur est identique a celle du serveur red5. Vous pouvez ajouter a la fin de l\'adresse le port si celui ci est different du port HTTP traditionnel.(ex : 123.456.789:8081)';
$string['adapter_file'] = 'Nom de fichier pour le plugiciel Red5 Adapter';
$string['adapter_file_help'] = 'Entrer le nom du fichier (sans extension) qui vous a été fourni par l\'administrateur Red5. Le plugiciel Red5 Adapter (RAP) est utilisé pour traiter les flux enregistrés sur le serveur Red5. Exemple: Lorsque vous supprimez une activité de Labo de langues OWLL, le RAP
                                    supprime les fichiers audio et vidéo qui y sont associés, permettant ainsi de garder le serveur Red5 libre de fichiers périmés. Dans des versions ultérieures, il sera aussi utilisé pour la sauvegarde de secours des fichiers audio et vidéos. Cette option n\'est pas encore disponible. Le nom de fichier est utilisé pour des raisons de sécurité. Si un intrus tente d\'accéder au serveur Red5, la page d\'accueil par défaut n\'apparaîtra pas.
                                    C\'est le seul fichier dans le répertoire, ce qui le rend plus difficile à deviner. Évidemment, la fonction permettant de parcourir le répertoire doit être désactivée.';
$string['use_mp3'] = 'Utiliser le mp3 téléversé comme piste de contrôle';
$string['use_mp3_help'] = 'Si vous utilisez un fichier téléversé, vous devez vous assurer que la case est cochée. Sinon, à la sauvegarde, l\'enregistrement manuel sera utilisé comme piste de contrôle. En revanche, si vous désirez utiliser l\'enregistrement manuel, assurez-vous que la case est désactivée. N\'oubliez pas de sauvegarder les changements.';
$string['master_track_recorder'] = 'Piste de contrôle';
$string['master_track_recorder_help'] = 'La piste de contrôle est un échantillon audio avec des espaces libres permettant aux étudiants de s\'enregistrer dans ces espaces libres.<br>Il suffit de cliquer le bouton rond d\'enregistrement et enregistrer l\'exercice. Par exemple, vous pouvez enregistrer l\'exercice suivant: 
                                <ul><li>
                                Dites le mot "bonjour" <i>(laissez 5 secondes de pause)</i> Maintenant, dites le mot "bonsoir" <i>(encore une fois, laissez 5 secondes de pause)</i>
                                </li></ul> Cet enregistrement jouera lorsque l\'étudiant cliquera sur le bouton d\'enregistrement. Pendant les pauses de 5 secondes, l\'étudiant peut répéter les mots et ceux-ci seront enregistrés. En tant qu\'enseignant, lorsque vous écoutez les enregistrements des étudiants, la piste de contrôle jouera avec la réponse des étudiants, permettant ainsi une comparaison aisée. <br>Note: Une telle intégration était nécessaire dans la mesure où Flash ne permet pas les pauses durant un enregistrement.';
$string['previous_recording'] = 'Votre enregistrement précédent: ';

//XML localization
$string['XMLLoadFail'] = 'Le fichier XML ne peut être trouvé. Prière de communiquer avec votre administrateur.';
$string['prerequisitesNotMet'] = 'Serveur non spécifié. Prière de communiquer avec votre administrateur.';
$string['warningLossOfWork'] = 'Vous tentez de naviguer hors d\'un enregistrement auquel vous avez apporté des modifications. Etes-vous sûr de vouloir annuler vos modifications?';
$string['newRecording'] = 'Nouvel enregistrement';
$string['newReply'] = 'Réponse de l\'enseignant';
$string['timesOut'] = 'Le temps est écoulé';
$string['submitBlank'] = 'Envoyer';
$string['submitNew'] = 'Soumettre l\'enregistrement';
$string['submitChanges'] = 'Envoyer les changements';

$string['subject'] = 'énoncé';
$string['message'] = 'Écrire votre message ici.';
$string['btnDiscard'] = 'Annuler les modifications';
$string['btnCancel'] = 'Annuler';
$string['submitBlank'] = 'Soumettre';
$string['submitNew'] = 'Soumettre un enregistrement';
$string['submitChanges'] = 'Soumettre un changement';
$string['submitGrade'] = 'Envoyer la note.';
$string['agoBefore'] = '';
$string['agoAfter'] = '';
$string['years'] = 'année';
$string['months'] = 'mois';
$string['weeks'] = 'semaines';
$string['days'] = 'jours';
$string['hours'] = 'heures';
$string['minutes'] = 'minutes';
$string['seconds'] = 'secondes';
$string['grading'] = 'Note';
$string['grade'] = 'Note';
$string['startOver'] = 'L\'élève doit recommencer';
$string['corrNotes'] = 'Entrer vos commentaires ici';
$string['recordings'] = 'Enregistrements';
$string['notesCorrection'] = 'Commentaires et correction';
$string['enableGradebook'] = 'Activer l\'intégration du carnet de notes pour accéder à l\'interface des notes';
$string['feedback'] = 'Rétroaction';
$string['privateNotes'] = 'Notes privées';

$string['monday'] = 'lun';
$string['tuesday'] = 'mar';
$string['wednesday'] = 'mer';
$string['thursday'] = 'jeu';
$string['friday'] = 'ven';
$string['saturday'] = 'sam';
$string['sunday'] = 'dim';
$string['january'] = 'jan';
$string['february'] = 'fév';
$string['march'] = 'mar';
$string['april'] = 'avr';
$string['may'] = 'mai';
$string['june'] = 'jui';
$string['july'] = 'juil';
$string['august'] = 'août';
$string['september'] = 'sept';
$string['october'] = 'oct';
$string['november'] = 'nov';
$string['december'] = 'déc';

//Criteria no longer used in the updated version
$string['criteria'] = 'Critère';
$string['student_recordings'] = 'Enregistrement des étudiants';
$string['teachers_comments'] = 'Vos commentaires';
$string['feedback'] = 'Les remarques de votre professeur';
$string['evaluation_failed_save'] = 'L\'évaluation n\'a pu être enregistrée à la base de données';
$string['evaluation_saved'] = 'Votre évaluation a été enregistrée';
$string['comments'] = 'Commentaires';
$string['recordcomments'] = 'Enregistrer vos commentaires';
$string['evaluation'] = 'Critère d\'évaluation';
$string['evaluation_instructions'] = 'Sélectionner tous les critères que vous aimeriez utiliser pour évaluer la soumission des étudiants';
$string['oral_comprhension'] = 'Compréhension orale';
$string['oral_response'] = 'Réponse orale';
$string['eval1'] = 'En mesure d\'identifier le thème ou la question de l\'épisode';
$string['eval2'] = 'En mesure de comprendre la nature de la question abordée dans l\'épisode';
$string['eval3'] = 'En mesure de comprendre l\'intention de l\'épisode';
$string['eval4'] = 'En mesure de comprendre les détails explicites de la question abordée dans l\'épisode';
$string['eval5'] = 'En mesure de comprendre les détails implicites de l\'épisode';
$string['eval6'] = 'En mesure d\'interpréter correctement les idées des autres';
$string['eval7'] = 'En mesure de comprendre les interventions de la personne qui parle';
$string['eval8'] = 'En mesure de parler et de s\'exprimer clairement';
$string['eval9'] = 'En mesure de parler et de s\'exprimer avec pertinence';
$string['eval10'] = 'En mesure de parler et de s\'exprimer de façon efficace';
$string['eval11'] = 'En mesure d\'expliquer une idée';
$string['eval12'] = 'En mesure de résumer l\'épisode ou l\'article de façon efficace et correcte';
$string['eval13'] = 'En mesure de présenter un point de vue tout en mettant en valeur les arguments ou les exemples pertinents';
$string['eval14'] = 'En mesure d\'expliciter les relations entre les idées';
$string['eval15'] = 'En mesure de confirmer, qualifier ou clarifier leurs idées ou opinions';
$string['eval16'] = 'En mesure de parler et de s\'exprimer avec subtilité et assurance';
$string['eval17'] = 'En mesure de dédure ou d\'induire logiquement et clairement';
$string['eval18'] = 'En mesure de comparer de façon claire et logique';
$string['eval19'] = 'En mesure d\'organiser leurs idées logiquement et clairement';
$string['eval20'] = 'En mesure de formuler des arguments percutants et pertinents';
$string['eval21'] = 'En mesure de sélectionner des exemples pertinents';
$string['eval22'] = 'En mesure de démontrer ou justifier la pertinence de tels exemples';
$string['eval23'] = 'En mesure de répondre aux arguments ou aux énoncés des autres afin de défendre sa position';
$string['eval24'] = 'En mesure de prendre l\'initiative dans la discussion';
$string['eval25'] = 'En mesure de prononcer correctement';
$string['eval26'] = 'En mesure de parler couramment et de s\'exprimer';
$string['eval27'] = 'En mesure de parler avec une fluidité naturelle';
$string['eval28'] = 'En mesure d\'utiliser un vocabulaire varié et approprié';
$string['eval29'] = 'En mesure d\'éviter les anglicismes';
$string['eval30'] = 'Possède une bonne maîtrise de la grammaire';
$string['eval31'] = 'En mesure de conjuguer les verbes correctement';
$string['eval32'] = 'En mesure d\'utiliser les bons temps de verbe';
$string['eval33'] = 'En mesure d\'utiliser les bonnes formes des verbes';
$string['eval34'] = 'Applique correctement la concordance des temps';
$string['eval35'] = 'En mesure d\'utiliser le bon genre, masculin ou féminin, des noms';
$string['eval36'] = 'En mesure d\'utiliser correctement les déterminants des noms';
$string['eval37'] = 'En mesure d\'accorder l\'adjectif au nom et le complément au sujet';
$string['eval38'] = 'En mesure d\'utiliser correctement les pronoms';
$string['eval39'] = 'En mesure d\'utilser un registre linguistique approprié à la situation de communication';

$string["advanced"] = 'Paramètres avancés';
$string["attempts_help"] = 'Cochez cette case si vous voulez limiter à un seul enregistrement par étudiant.';
$string["async"] = 'Discussion (forum like)';
$string["dialogue"] = 'Dialogue';
$string["group_type"] = 'Group type';
$string["group_type_help"] = '<b>Note: Only use this setting if you are using seperate groups or visible groups</b><br>
                               <ul><li><i>Discussion:</i> Use this type if you would like your students to record asynchronously. A forum like thread will display the conversation</li>
                               <li><i>Dialogue:</i> Use this type if you want your group of students to have a recorded conversation.</li></ul>';
$string['max_users'] = 'Maximum number of users.';
$string['max_users_help'] = 'Maximum number of users that can use the language lab simultaniously.';

$string['languagelab:studentview'] = 'Language lab: Student view.';
$string['languagelab:teacherview'] = 'Language lab: Teacher view.';
$string['languagelab:manage'] = 'Language lab: Manager.';
$string['select_group_type'] = 'Select group type';


$string['classmonitor'] = 'Moniteur de classe';
$string['salt'] = 'valeur du \'salt\':';
$string['salt_help'] = 'Enter the password salt value for your red5 instance as provided by your red5 administrator.';
$string['stealthMode'] = 'Activer le mode furtif?';
$string['stealthMode_help'] = 'Lorsque ce mode est activé, les étudiants ne sont pas avertis que l\'instructeur écoute leur travail.';
$string["use_video"] = 'Allow video.';
$string["use_grade_book_help"] = 'Par défaut, les activités du Labo de langues OWLL ne sont pas notées. Ainsi, vous pouvez créer de nombreuses activités sans remplir le carnet de notes. Si vous voulez noter cette activité, cochez cette case.';
$string["video"] = 'Allowing video.';
$string["video_help"] = 'Check this box if you would like your students to use video and audio while recording. This can be helpful, for example, for sign language.';

$string["nonStreamingBasePath"] = 'HTTP Path to audio/video files.';
$string["nonStreamingBasePath_help"] = 'This is a path to the actual audio/video files through http. This is required for improved scrubbing. You can leave this blank if you do not have this path.';
$string["norappermission"] = "You do not have the required permissions to view this page.";



$string['cancel'] = 'Annuler';
$string['classmonitor_help'] = 'Cliquez ici pour accéder au moniteur de classe';
$string['confirmDeleteHistory'] = 'Êtes-vous sûr de vouloir supprimer l\'enregistrement?';
$string['connectClient'] = 'En train de se connecter...';
$string['connected_student'] = 'Vous êtes maintenant connecté avec l\'étudiant. Cliquez sur le bouton "Terminer" pour terminer la discussion';
$string['connected_student_btnStop'] = 'Terminer';
$string['connected_student_title'] = 'Connecté avec l\'étudiant';
$string['connected_class'] = 'Vous êtes maintenant connecté avec la classe. Cliquez sur le bouton "Terminer" pour terminer la discussion';
$string['connected_class_btnStop'] = 'Terminer';
$string['connected_class_title'] = 'Connecté avec la classe';
$string['connected_no_student_connected'] = 'Aucun étudiant n\'est connecté';
$string['connected_error'] = 'Erreur de connexion';
$string['connectiongServer'] = 'En train de se connecter au serveur...';
$string['content'] = 'Labo de Langues';
$string['defaultTitleNewRecording'] = 'Nouvel enregistrement';
$string['deleteRecord'] = 'Supprimer';
$string['deleteRecord_help'] = 'Sélectionner un fichier et cliquer pour le supprimer ';
$string['disconnectClient'] = 'En train de déconnecter...';
$string['downloadRecord'] = 'Télécharger';
$string['downloadRecord_help'] = 'Cliquer pour télécharger un enregistrement';
$string['error_activity_not_available'] = 'Cette activité n\'est plus disponible';
$string['error_activity_not_available_delete'] = 'Cette activité n\'est plus disponible et ne peut donc pas être supprimée';
$string['error_cannot_connect_student'] = 'Incapable de se connecter avec l\'étudiant';
$string['error_delete_notexists'] = 'Fichier non trouvé... il n\'a pu être supprimé';
$string['error_delete_permission'] = 'Vous n\'avez pas la permission de supprimer ce fichier';
$string['error_grade_notsaved'] = 'Une erreur s\'est produite durant la sauvegarde de la note. La note n\'a pas été sauvegardée.';
$string['error_grade_permission'] = 'Vous n\'avez pas la permission d\'attribuer une note à l\'étudiant';
$string['error_grade_user_notexists'] = 'Cet utilisateur n\'existe pas dans cette activité. La note n\'a pas été sauvegardée.';
$string['error_insert_feedback_parent_notexists'] = 'L\'enregistrement de l\'étudiant n\'existe pas. Les remarques ne peuvent être ajoutées. ';
$string['error_insert_feedback_permission'] = 'Vous n\'avez pas la permission d\'écrire des remarques';
$string['error_missing_camera'] = 'Webcam non détectée. Branchez une webcam et redémarrer le navigateur.';
$string['error_missing_microphone'] = 'Microphone non détecté. Branchez un microphone et redemarrer votre navigateur.';
$string['error_record_save'] = 'Une erreur s\'est produite. L\'enregistrement n\'a pas été sauvegardé';
$string['error_user_max_attempts'] = 'Vous ne pouvez plus faire d\'enregistrements. Vous devez supprimer un enregistrmenet pour pouvoir en créer un nouveau';
$string['errorTitle'] = 'Erreur';
$string['filterStudents'] = 'Filtre d\'étudiants: ';
$string['filterStudents_help'] = 'Entrez le nom de l\'étudiant afin de faire afficher uniquement cet étudiant';
$string['fullscreen_student'] = 'Interface étudiant en plein écran';
$string['fullscreen_student_help'] = 'Si l\'option est cochée, le Labo de Langues apparaitra en plein écran pour l\'étudiant. (L\'affichage depend du thème)';
$string['goBackCourse'] = 'Retourner au cours';
$string['goBackCourse_help'] = 'Retourner au cours';
$string['gradeStudentWithRecordings'] = 'Seuls les étudiants avec au moins un enregistrement peuvent recevoir une note';
$string['listened'] = 'Votre professeur est en train de vous écouter';
$string['listRecordings'] = 'Enregistrements';
$string['listRecordings_help'] = 'Cliquer ici pour ouvrir un enregistrement dans une nouvelle fenêtre';
$string['LLnext'] = 'Suivant';
$string['LLnext_help'] = 'Aller au prochain Labo de Langues';
$string['LLprevious'] = 'Précedent';
$string['LLprevious_help'] = 'Aller au précedent Labo de Langues';
$string['load_prev_master'] = 'Revenir à la piste de contrôle précédente';
$string['micConfig'] = 'Configuration';
$string['newRecording'] = 'Nouvel enregistrement';
$string['newMastertrack'] = 'Nouvelle piste de contrôle';
$string['note_play_mastertrack'] = '<i><b>Note: </b> Si vous avez téléversé une piste de contrôle en MP3, cet enregistrement ne pourra pas être joué dans le lecteur ci-dessus tant que le formulaire n\'a pas été sauvegardé.</i>';
$string['playeroptionsBtnOk'] = 'OK';
$string['playeroptionstxt1'] = 'Pour utiliser le labo de langues OWLL, vous devez permettre l\'accès à votre microphone. Pour ce faire:';
$string['playeroptionstxt2'] = 'Sur l\'onglet "{$a} Privacy"';
$string['playeroptionstxt3'] = 'Sélectionner "{$a} Allow"';
$string['playeroptionstxt4'] = 'Cocher la case {$a} à côté de Remember';
$string['playeroptionstxt5'] = 'Cliquer sur Close';
$string['playeroptionstxt6'] = 'Cliquer sur OK en bas de la fenêtre pour sauvegarder les changements.';
$string['prev_next_lab'] = 'Ajouter les boutons Précédent/Suivant';
$string['prev_next_lab_help'] = 'Ajouter deux boutons Précedent et Suivant en bas de l\'activité pour pouvoir naviguer entre Labo de Langues.';
$string['raiseHand'] = 'Lever la main';
$string['raiseHand_help'] = 'Cliquer ici pour lever la main';
$string['recordingsHistory'] = 'Enregistrements';
$string['recordingRequired'] = 'Vous devez vous enregistrer avant de soumettre';
$string['recordingTitle'] = 'Titre';
$string['folder'] = 'Le nom du répertoire sur le serveur Red5 où les fichiers sont enregistrés';
$string['folder_help'] = 'Écrire le nom du fichier dans lequel sont sauvegardés les fichiers pour CETTE instance de Moodle.';
$string['red5serverprotocol'] = 'Le protocol de votre serveur RTMP';
$string['red5serverprotocol_help'] = 'Le protocol utilisé par votre serveur RTMP. En general rtmp.';
$string['reFeedBack'] = 'Au sujet de: ';
$string['refresh'] = 'Mettre à jour l\'historique';
$string['search'] = 'Rechercher: ';
$string['secondsRefreshClassmonitor'] = 'Nombre de microsecondes avant la prochaine mise à jour automatique du moniteur de classe';
$string['secondsRefreshClassmonitor_help'] = 'Inscrire le nombre de microsecondes entre les mises à jour automatiques de la liste d\'étudiants du moniteur de classe';
$string['secondsRefreshHistory'] = 'Nombre de microsecondes avant la prochaine mise à jour automatique de l\'historique';
$string['secondsRefreshHistory_help'] = 'Inscrire le nombre de microsecondes entre les mises à jour automatiques des enregistrements';
$string['secondsRefreshStudentView'] = 'Nombre de microsecondes avant la prochaine mise à jour automatique des étudiants';
$string['secondsRefreshStudentView_help'] = 'Inscrire le nombre de microsecondes entre les mises à jour automatiques des étudiants. Utilisé pour le flux en direct';
$string['speakToClass'] = 'S\'adresser à la classe';
$string['speakToClasshelp'] = 'Permission de parler en direct à tous les étudiants en ligne';
$string['stealth'] = 'Mode furtif';
$string['stealthActive'] = 'Actuellement actif';
$string['stealthInactive'] = 'Actuellement inactif';
$string['student_delete_recordings'] = 'Permettre aux étudiants de supprimer leurs enregistrements.';
$string['student_delete_recordings_help'] = 'Si cette case est cochée, les étudiants pourront supprimer leurs enregistrements. Exceptions : Si l\'enseignant a déjà fait une réponse à cet enregistrement ou si l\'activité n\'est plus disponible.';
$string['studentsOnline'] = 'Étudiants connectés';
$string['studentsOffline'] = 'Étudiants non connectés';
$string['student_recording'] = 'Enregistrement de l\'étudiant';
$string['submitGrade'] = 'Soumettre';
$string['submitGrade_help'] = 'Cliquer pour soumettre une note d\'étudiant';
$string['submitRecord'] = 'Soumettre';
$string['submitRecord_help'] = 'Cliquer pour soumettre';
$string['talkToStudent_help'] = 'Cliquer ici pour s\'adresser à cet étudiant';
$string['titleConfirm'] = 'Confirmation';
$string['teacher_class_speak'] = '<div class="dialogTeacherPic">{$a}</div> <br />s\'adresse à la classe';
$string['teacher_class_speak_title'] = 'Le professeur s\'adresse à la classe';
$string['teacher_student_speak'] = '<div class="dialogTeacherPic">{$a}</div> <br />a commencé une discussion avec vous. Vous êtes maintenant connecté.';
$string['teacher_student_speak_title'] = 'Discussion avec le professeur';
$string['titlePlayerOptions'] = 'Options du microphone';
$string['titleRecording'] = 'Enregistrement';
$string['titleStudentRecording'] = 'Enregistrement de l\'étudiant';
$string['thumbsup_student'] = 'Bien fait!';
$string['thumbsUp_help'] = 'Cliquer ici pour signifier votre approbation à cet étudiant';
$string['video_unavailable'] = 'Fonction vidéo en développement.';

$string['languagelab:addinstance'] = 'Ajouter un nouveau Labo de langues OWLL';
$string['languagelab:view'] = 'Afficher le labo de langues OWLL';

$string['simplified_interface_student'] = 'Interface simplifiée pour étudiant';
$string['simplified_interface_student_help'] = 'Si vous souhaitez une interface simplifiée pour les étudiants avec seulement l\'enregistreur et le bouton de validation. Seulement disponible lorsque "Un seul enregistrement par étudiant" est coché.';
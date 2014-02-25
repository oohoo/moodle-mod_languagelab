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
$string['name'] = 'Name';
$string['modulename'] = 'Language lab - OWLL';
$string['modulenameplural'] = 'Language labs - OWLL';
$string['modulename_help'] = 'OWLL (OOHOO Web-based Language Lab) is a Moodle plugin replicating all functions of a traditional language lab, with added features that stem from its being web-based. 
It allows for synchronous as well as asynchronous use, in a face-to-face as well as in a distance learning setting.';
$string['modulename_link'] = 'http://oohoo.biz/index.php/en/plug-ins/language-learning/owll-oohoo-web-based-language-lab/';
$string['languagelab'] = 'Language lab - OWLL';
$string['pluginadministration'] = 'Language lab - OWLL';
$string['pluginname'] = 'Language lab - OWLL';
$string['red5server'] = 'Path to your Red5 server';
$string['red5server_help'] = 'Enter the IP address or the fully qualified name for your red5 server. Localhost will not work! You can specify the port if different from default at the end (ex: 123.456.789:12345)';
$string['red5serverfolder'] = 'Path to your Red5 server folder (ex: oflaDemo)';
$string['red5serverfolder_help'] = 'Your RTMP server first folder. This folder is the application that read and record files.';
$string['red5serverprotocol'] = 'Your RTMP server protocol';
$string['red5serverprotocol_help'] = 'The protocol of your RTMP server. Generally rtmp.';
$string['red5config'] = 'Enter the IP address or the fully qualified name for your red5 server. Localhost will not work!';
$string['name'] = 'Activity name';
$string['description'] = 'Description';
$string['availabledate'] = 'Available from: ';
$string['duedate'] = 'Due date: ';
$string['general'] = 'General';
$string['attempts'] = 'One recording per student';
$string['recording_timelimit'] = 'Recoding time limit in minutes. (0 = unlimited)';
$string['attempts_warning'] = 'Note: Only one submission is allowed.<br>You can return and record over the previous submission. This will delete the previous recording.<br>Only the last submitted recording will be evaluated';
$string['master_track'] = 'Master track (mp3 only)';
$string['master_track_file'] = 'Master track currently used';
$string['master_track_help'] = 'Similarily, if you have previous recordings in MP3 format, you can choose to upload the MP3 file and use it as the Master Track.
                                If you do use the MP3 file, it is important that you check the following checkbox \'<i>Use the Uploaded MP3 as Master Track.</i>\' Otherwise, the manually 
                                recorded track above will be used.<br>If you have previously uploaded an MP3, but would like to use the manual recording, uncheck the checkbox
                                from \'<i>Use the Uploaded MP3 as Master Track.</i>\' ';
$string['not_available'] = 'This activity expired. You can read/listen to your teachers notes. However, you will be unable to do any new recordings.';
$string['no_due_date'] = 'No due date entered.';
$string['no_available_date'] = 'No start date entered.';
$string['submit'] = 'Submit';
$string['recorderdescription'] = 'Recorder';
$string['use_grade_book'] = 'Use grade book';
$string['emailsubject'] = 'Activity - ';
$string['emailgreeting'] = 'Hello';
$string['emailbodynewreply'] = 'I have added a comment to your recording. Please return to the activity and listen/read my comments. ';
$string['emailbodydelete'] = 'I would like you to restart your recording. Please return to the activity and record yourself again. ';
$string['emailthankyou'] = 'Thank you';
$string['words'] = 'List of words for the students to record';
$string['prefix'] = 'Enter a prefix for recorded streams.';
$string['prefix_help'] = 'The prefix here is usefull if the red5 server is used to stream other material. You will be able to easily identify recorded streams';



$string['submit_recording'] = 'Submit your recording';
$string['recording_failed_save'] = 'Failed to save your recording to the database';
$string['recording_saved'] = 'Your recording has been submitted';
$string['recording_exists'] = 'Notice: you already have a recording. Please review the recording before pressing the record button.<br>Pressing the record button will erase the previous recording.';
$string['adapter_access'] = 'Secure access to Red5 Adapter Plugin?';
$string['adapter_access_help'] = 'Is the RAP server protected with a certificate? ie: https';
$string['adapter_server'] = 'Server address to access to the adapter.';
$string['adapter_server_help'] = 'Server address to access to the adapter. Generally the same as the Red5 server. You can add a port at the end of the server address if different from the classic http port. (ex: 123.456.789:8081)';
$string['adapter_file'] = 'File name for the Red5 Adapter Plugin';
$string['adapter_file_help'] = 'Enter the file name (with no extension) given to you by the Red5 administrator. The Red5 Adapter Plugin (RAP) is used to manipulate streams recorded on the Red5 Server. Example: When deleting a Language Lab activity, the RAP
                                    deletes the associated audio and video files, keeping the Red5 Server clean. In the next release, It will also be used to backup the audio and video files
                                    when doing a Moodle backup. This feature is not yet available. The file name is used for security reasons. This way if someone accesses the Red5 server, no default home page will show up. 
                                    This is the only file in the folder, making it harder to figure out. Of course, directory browsing must be disabled.';
$string['use_mp3'] = 'Use the Uploaded MP3 as master track?';
$string['use_mp3_help'] = 'If you use an uploaded file, you want to make sure that the check box is checked. Otherwise, when saving, the Manual recording will be
                            used as the Master track. On the other hand, if you do want to use the manual recording, make sure that this check box is unchecked. Remember to save the changes';
$string['master_track_recorder'] = 'Master track';
$string['master_track_recorder_help'] = 'A master track is an audio sample with blank spaces allowing students to
                                record themselves during the blank spaces.<br>Simply press the round record button and record your
                                exercise. For example, you could record the following exercise:
                                <ul><li>
                                Please say the word bonjour <i>(leave a 5 second pause)</i> Now, say the words bon soir <i>(again, leave a 5 second pause)</i>
                                </li></ul> This recording will play back when the student presses the record button. During the 5 second pauses, the student can say the
                                words and they will be recorded. As the teacher, when you listen to the student recordings, the Master Track will be played with the students response
                                allowing you to easliy compare.<br>Note: We had to integrate in this fashion because Flash does not
                                allow pausing while recording.';
$string['previous_recording'] = 'Your previous recordings: ';

//XML localization
$string['XMLLoadFail'] = 'XML couldn\'t be loaded. Contact your webmaster.';
$string['prerequisitesNotMet'] = 'Target server unspecified. Contact your webmaster.';
$string['warningLossOfWork'] = 'You are attempting to navigate away from a recording on which you made submitable changes. Are you sure you want to discard your changes?';
$string['newRecording'] = 'New recording';
$string['newReply'] = 'Teacher reply';
$string['timesOut'] = 'Time is up';

$string['subject'] = 'Recording';
$string['message'] = 'You may type a message here.';
$string['btnDiscard'] = 'Discard changes';
$string['btnCancel'] = 'Cancel';
$string['submitBlank'] = 'Submit';
$string['submitNew'] = 'Submit recording';
$string['submitChanges'] = 'Submit changes';
$string['submitGrade'] = 'Submit grade.';
$string['agoBefore'] = '';
$string['agoAfter'] = 'ago';
$string['years'] = 'years';
$string['months'] = 'months';
$string['weeks'] = 'weeks';
$string['days'] = 'days';
$string['hours'] = 'hours';
$string['minutes'] = 'minutes';
$string['seconds'] = 'seconds';
$string['grading'] = 'Grading';
$string['grade'] = 'Grade';
$string['startOver'] = 'Require student to start over';
$string['corrNotes'] = 'Enter correction notes here';
$string['recordings'] = 'Recordings';
$string['notesCorrection'] = 'Notes & Correction';
$string['enableGradebook'] = 'Enable gradebook integration to access grading interface';
$string['feedback'] = 'Feedback';
$string['privateNotes'] = 'Private Notes';


$string['monday'] = 'Mon';
$string['tuesday'] = 'Tues';
$string['wednesday'] = 'Wed';
$string['thursday'] = 'Thur';
$string['friday'] = 'Fri';
$string['saturday'] = 'Sat';
$string['sunday'] = 'Sun';
$string['january'] = 'Jan';
$string['february'] = 'Feb';
$string['march'] = 'Mar';
$string['april'] = 'Apr';
$string['may'] = 'May';
$string['june'] = 'Jun';
$string['july'] = 'Jul';
$string['august'] = 'Aug';
$string['september'] = 'Sept';
$string['october'] = 'Oct';
$string['november'] = 'Nov';
$string['december'] = 'Dec';

$string["advanced"] = 'Advanced settings';
$string["attempts_help"] = 'Check this box if you want your students to record only to one file.';
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


$string['classmonitor'] = 'Monitor your class';
$string['salt'] = 'Password salt value:';
$string['salt_help'] = 'Enter the password salt value for your red5 instance as provided by your red5 administrator.';
$string['stealthMode'] = 'Activate stealth mode?';
$string['stealthMode_help'] = 'When activated, students will not know that they are being monitored when the teacher uses the classroom monitor.';
$string["use_video"] = 'Allow video';
$string["use_grade_book_help"] = 'By default, no grading will be given for language lab activities. That way you can create as many language activities
                                   for exercise purposes, without filling up your gradebook. If you do want to grade this particular activity, check this box.';
$string["video"] = 'Allowing video.';
$string["video_help"] = 'Check this box if you would like your students to use video and audio while recording. This can be helpful, for example, for sign language.';

$string["nonStreamingBasePath"] = 'HTTP Path to audio/video files.';
$string["nonStreamingBasePath_help"] = 'This is a path to the actual audio/video files through http. This is required for improved scrubbing. You can leave this blank if you do not have this path.';
$string["norappermission"] = "You do not have the required permissions to view this page.";





$string['cancel'] = 'Cancel';
$string['classmonitor_help'] = 'Click here to access to the class monitor';
$string['confirmDeleteHistory'] = 'Are you sure you want to delete recording ';
$string['connectClient'] = 'Connecting...';
$string['connected_student'] = 'You are now connected with the student. Click on the button Close to finish the discussion';
$string['connected_student_btnStop'] = 'Close';
$string['connected_student_title'] = 'Connected with a student';
$string['connected_class'] = 'You are now connected with the class. Click on the button Close to finish the discussion';
$string['connected_class_btnStop'] = 'Close';
$string['connected_class_title'] = 'Connected with the class';
$string['connected_no_student_connected'] = 'No student connected';
$string['connected_error'] = 'Connection error';
$string['connectiongServer'] = 'Connecting to server...';
$string['content'] = 'Language lab';
$string['defaultTitleNewRecording'] = 'New Recording';
$string['deleteRecord'] = 'Delete';
$string['deleteRecord_help'] = 'Select a record and click to delete it';
$string['disconnectClient'] = 'Disconnecting...';
$string['downloadRecord'] = 'Download';
$string['downloadRecord_help'] = 'Click to download Recording';
$string['error_activity_not_available'] = 'This activity is no longer available';
$string['error_activity_not_available_delete'] = 'This activity is no longer available, deletion not possible';
$string['error_cannot_connect_student'] = 'Cannot connect to the student';
$string['error_delete_notexists'] = 'This record was not found, deletion not completed';
$string['error_delete_permission'] = 'You don\'t have the permission to delete this record';
$string['error_grade_notsaved'] = 'An error has occured during the saving of the grade. Grade not saved.';
$string['error_grade_permission'] = 'You don\'t have the permission to grade a student';
$string['error_grade_user_notexists'] = 'This user not exists for this activity. Grade not saved';
$string['error_insert_feedback_parent_notexists'] = 'The student recording does not exist, the feedback can not be inserted';
$string['error_insert_feedback_permission'] = 'You don\'t have the permission to create a feedback';
$string['error_missing_camera'] = 'No camera detected. Please, connect a camera and restart the browser.';
$string['error_missing_microphone'] = 'No microphone detected. Please, connect a microphone and restart the browser.';
$string['error_record_save'] = 'An error has occured, recording not saved';
$string['error_user_max_attempts'] = 'You can\'t create anymore recordings. You have to delete one recording in order to create a new one';
$string['errorTitle'] = 'Error';
$string['filterStudents'] = 'Students filter: ';
$string['filterStudents_help'] = 'Type student\'s name to display only this student';
$string['fullscreen_student'] = 'Student interface in fullscreen';
$string['fullscreen_student_help'] = 'If this option is checked, student will have the language lab in fullscreen mode without a header/footer or blocs. (depends on the theme)';
$string['goBackCourse'] = 'Go back to course';
$string['goBackCourse_help'] = 'Go back to course';
$string['gradeStudentWithRecordings'] = 'Only students with at least one recording can be graded';
$string['listened'] = 'Your teacher is listening you right now';
$string['listRecordings'] = 'Recordings';
$string['listRecordings_help'] = 'Click here to open the recordings in a pop-up';
$string['LLnext'] = 'Next';
$string['LLnext_help'] = 'Go to next language lab';
$string['LLprevious'] = 'Previous';
$string['LLprevious_help'] = 'Go to previous language lab';
$string['load_prev_master'] = 'Revert to previous mastertrack';
$string['micConfig'] = 'Configuration';
$string['newRecording'] = 'New Recording';
$string['newMastertrack'] = 'New Mastertrack';
$string['note_play_mastertrack'] = '<i><b>Note: </b> If you upload a MP3 master track, this sound will not be able to be played in the player above until you save this form.</i>';
$string['playeroptionsBtnOk'] = 'Ok';
$string['playeroptionstxt1'] = 'In order to use the Language Lab, you need to authorize access to your microphone. To do so:';
$string['playeroptionstxt2'] = 'On the tab "{$a} Privacy"';
$string['playeroptionstxt3'] = 'Select "{$a} Allow"';
$string['playeroptionstxt4'] = 'Check the {$a} box next to Remember';
$string['playeroptionstxt5'] = 'Click on Close';
$string['playeroptionstxt6'] = 'Click on OK at the bottom of the window to save your changes.';
$string['prev_next_lab'] = 'Add Prev/Next buttons';
$string['prev_next_lab_help'] = 'Add two buttons Previous and Next at the bottom of the activity in order to go to the previous or next language lab of the course.';
$string['raiseHand'] = 'Raise hand';
$string['raiseHand_help'] = 'Click here to raise your hand to your teacher';
$string['recordingsHistory'] = 'Recordings';
$string['recordingRequired'] = 'You have to record before submit';
$string['recordingTitle'] = 'Title';
$string['folder'] = 'The name of the folder on the Red5 Server where files are saved';
$string['folder_help'] = 'Type the name of the folder where are saved files for THIS moodle.';
$string['reFeedBack'] = 'Re: ';
$string['refresh'] = 'Refresh History';
$string['search'] = 'Search: ';
$string['secondsRefreshClassmonitor'] = 'Nb of microseconds before the next auto class monitor refresh';
$string['secondsRefreshClassmonitor_help'] = 'Type the number of microseconds to auto refresh the students list in the class monitor.';
$string['secondsRefreshHistory'] = 'Nb of microseconds before the next auto history refresh';
$string['secondsRefreshHistory_help'] = 'Type the number of microseconds to auto refresh the recordings.';
$string['secondsRefreshStudentView'] = 'Nb of microseconds before the next auto refresh for students';
$string['secondsRefreshStudentView_help'] = 'Type the number of microseconds to auto refresh the student view. Used for the live';
$string['speakToClass'] = 'Speak to class';
$string['speakToClasshelp'] = 'Allow you to speak in live to all online students';
$string['stealth'] = 'Stealth';
$string['stealthActive'] = 'Currently Active';
$string['stealthInactive'] = 'Currently Inactive';
$string['student_delete_recordings'] = 'Allow students to delete their recordings';
$string['student_delete_recordings_help'] = 'If this checkbox is checked, students can delete their recordings, except: if there is a teacher answer to this recording, the activity is unavailable';
$string['studentsOnline'] = 'Students online';
$string['studentsOffline'] = 'Students offline';
$string['student_recording'] = 'Students recording';
$string['submitGrade'] = 'Submit';
$string['submitGrade_help'] = 'Click to submit the student grade';
$string['submitRecord'] = 'Submit';
$string['submitRecord_help'] = 'Click to submit your record';
$string['talkToStudent_help'] = 'Click here to talk to this student';
$string['titleConfirm'] = 'Confirmation';
$string['teacher_class_speak'] = '<div class="dialogTeacherPic">{$a}</div> <br />talk to the class';
$string['teacher_class_speak_title'] = 'The teacher speak to the class';
$string['teacher_student_speak'] = '<div class="dialogTeacherPic">{$a}</div> <br />has started a discussion with you. You are now connected.';
$string['teacher_student_speak_title'] = 'Discussion with a teacher';
$string['titlePlayerOptions'] = 'Microphone options';
$string['titleRecording'] = 'Recording';
$string['thumbsup_student'] = 'Good Job!';
$string['thumbsUp_help'] = 'Clic here to send a &quot;Thumbs Up&quot; to this student';
$string['video_unavailable'] = 'Video option under development.';

$string['languagelab:addinstance'] = 'Add a new Language lab';
$string['languagelab:view'] = 'View Language lab';

$string['deletealldata'] = 'Delete all students recordings';
$string['deletealldata_notice'] = '<b>Warning</b>: Deleting all students data is irreversible.';

$string['adminconfig_header'] = 'After complete, you can click <a href="{$a}">here</a> to test the RAP configuration.';

$string['simplified_interface_student'] = 'Simplified interface for students';
$string['simplified_interface_student_help'] = 'If you want a simplified interface for the student with just the recorder and the submit button. Only available if "one recording per student" is activated.';

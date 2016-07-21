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
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once("$CFG->dirroot/lib/resourcelib.php");
require_once("$CFG->dirroot/lib/filestorage/file_storage.php");
require_once("locallib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l = optional_param('l', 0, PARAM_INT);  // languagelab ID

global $CFG, $DB, $PAGE;


if ($id)
{
    $cm = get_coursemodule_from_id('languagelab', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $languagelab = $DB->get_record('languagelab', array('id' => $cm->instance), '*', MUST_EXIST);
}
elseif ($l)
{
    $languagelab = $DB->get_record('languagelab', array('id' => $l), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $languagelab->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('languagelab', $languagelab->id, $course->id, false, MUST_EXIST);
    $id = $cm->id;
}
else
{
    error('You must specify a course_module ID or an instance ID');
}

//Create the folder on the RTMP server in case it does not already exists
languagelab_adapter_call('create_folder', 's=' . $CFG->languagelab_folder . '/' . $id);

require_login($course, true, $cm);

//Moodle 2.5 JQUERY condition
if (!method_exists(get_class($PAGE->requires), 'jquery'))
{
    $PAGE->requires->js('/mod/languagelab/js/jquery-1.10.2.min.js', true);
    $PAGE->requires->js('/mod/languagelab/js/jquery.ui/jquery-ui-1.10.3.custom.min.js', true);
    $PAGE->requires->css('/mod/languagelab/js/jquery.ui/custom-theme/jquery-ui-1.10.3.custom.min.css');
}
else
{
    $PAGE->requires->jquery();
    $PAGE->requires->jquery_plugin('ui');
    $PAGE->requires->jquery_plugin('ui-css');
}
$PAGE->requires->js('/mod/languagelab/js/jquery.jstree/jquery.jstree.js', true);
$PAGE->requires->js('/mod/languagelab/js/flash_detect_min.js', true);
$PAGE->requires->js('/mod/languagelab/js/languagelab.js', true);

$PAGE->requires->css('/mod/languagelab/style.css');

$embed = optional_param('embed', false, PARAM_BOOL);
if ($embed)
{
    //Change the layout to embedded
    $PAGE->set_pagelayout('embedded');
    //Keep the ccs just in case
    $PAGE->requires->css('/mod/languagelab/style-embed.css');
}

//Only for Moodle < 2.7
if(!function_exists('moodle_major_version') || moodle_major_version() < '2.7')
{
    add_to_log($course->id, 'languagelab', 'view', "view.php?id=$cm->id", $languagelab->name, $cm->id);
}
else
{
    //TODO Log for Moodle 2.7+
}

/// Print the page header

$PAGE->set_url('/mod/languagelab/view.php', array('id' => $cm->id));
$PAGE->set_title($languagelab->name);
$PAGE->set_heading($course->shortname);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('languagelab', 'languagelab')));

//Replace get_context_instance by the class for moodle 2.6+
if(class_exists('context_module'))
{
    $context = context_module::instance($cm->id);
    $contextcourse = context_course::instance($course->id);
}
else
{
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $contextcourse = get_context_instance(CONTEXT_COURSE, $course->id);
}

//Get the fullscreen mode param
if ($languagelab->fullscreen_student && !has_capability('mod/languagelab:teacherview', $context, null, true))
{
    $PAGE->set_pagelayout('embedded');
}

if (groups_get_activity_groupmode($cm, $course) == SEPARATEGROUPS)
{
    $groupid = get_current_group($course->id);
}
else
{
    $groupid = 0;
}

//print_object($context);
// Output starts here
echo $OUTPUT->header();


/// Print the main part of the page
echo $OUTPUT->box_start();




//Definition of the main variables kept in JS
echo '
<script type="text/javascript">
    var playerRecorders = [];
    var playerOptions;
    var userLiveURI;
    var userRecordURI;

</script>';

$content = file_rewrite_pluginfile_urls($languagelab->description, 'pluginfile.php', $context->id, 'mod_languagelab', 'content', $languagelab->id);
$formatoptions = array('noclean' => true, 'overflowdiv' => true);
$content = format_text($content, $languagelab->contentformat, $formatoptions, $course->id);

$descrClassStudent = '';
$classSimplifiedInterface = '';
if (!has_capability('mod/languagelab:teacherview', $context, null, true))
{
    $descrClassStudent = 'descrLabLangStudent';
    if ($languagelab->simplified_interface_student == 1)
    {
        $classSimplifiedInterface = 'simplified_interface';
    }
}



echo $OUTPUT->box($content, 'generalbox center clearfix ' . $descrClassStudent, 'descrLabLang');

echo '<div align=\'center\' class="' . $classSimplifiedInterface . '">';

//************************Get teacher information*****************************************
$user = $DB->get_record("user", array("id" => $USER->id));
$userpictureurl = $OUTPUT->user_picture($user, array('courseid' => $course->id, 'link' => false));
//create an array from the image tag
$newuserpictureurl = explode(' ', $userpictureurl);
//Get the link info from array row 1 and remove src="
$userpicture = str_replace('src="', '', $newuserpictureurl[1]);
//remove last double quotation marks;
$userpicture = str_replace('"', '', $userpicture);
//
//************************Get language activity params*******************************
$languagelab_params = $DB->get_record('languagelab', array('id' => $languagelab->id));

$now = time();
$available = $languagelab_params->timeavailable < $now && ($now < $languagelab_params->timedue || !$languagelab_params->timedue);
//************************End language activity params******************************
//************************Get master track*******************************
if (isset($languagelab->master_track))
{
    //check to find out if MP3.
    if (strpos($languagelab->master_track, '.mp3') == false)
    {
        //Check if the mastertrack recording exists
        $mastertrack_exists = languagelab_adapter_call('check_file_exist', "p=$languagelab->master_track");
        //If the mastertrack exists OR if the result is empty (because the RAP is not well configured)
        if($mastertrack_exists == 1 || $mastertrack_exists == '')
        {
            $mastertrack = $languagelab->master_track;
        }
        else
        {
            $mastertrack = '';
        }
    }
    else
    {
        $mastertrack = moodle_url::make_pluginfile_url($context->id, 'mod_languagelab', 'mastertrack', 0, '/', $languagelab->master_track);
    }
}
else
{
    $mastertrack = '';
}
//************************End Get master track*******************************
// if attempts is true, it must equal 0
if ($languagelab_params->attempts == 1)
{
    $attempts = 0;
}
else
{
    $attempts = 1;
}

$playerHeight = 45;
$classVideo = '';
if ($languagelab->video != 0)
{
    $playerHeight = 45 + 262;
    $classVideo = 'playerVideo';
}

//Info for the availability
if (!$available)
{
    echo $OUTPUT->box('<h3>' . get_string('error_activity_not_available', 'mod_languagelab') . '</h3>', 'generalbox center clearfix');
}

echo '<script type="text/javascript">';
echo '  var allowDelete = ' . ($languagelab->student_delete_recordings && $available ? 'true' : 'false') . ';';
echo '  var available = ' . (($available) ? 'true' : 'false') . ';';
echo '  var onlyOneRecording = ' . (($languagelab->attempts == 1) ? 'true' : 'false') . ';';
echo '  var checksum = "";';
echo '  var selectedElem = null;';
echo '  var selectedUser = null;';
echo '  var secondsRefreshHistory = ' . $CFG->languagelab_secondsRefreshHistory . ';';
echo '  var secondsRefreshStudentView = ' . $CFG->languagelab_secondsRefreshStudentView . ';';
echo '  var activityid = ' . $id . ';';
echo '  var cancel = "' . get_string('cancel', 'languagelab') . '";';
echo '  var deleteRecord = "' . get_string('deleteRecord', 'languagelab') . '";';
echo '  var titleConfirm = "' . get_string('titleConfirm', 'languagelab') . '";';
echo '  var lblConfirmDelete = "' . get_string('confirmDeleteHistory', 'languagelab') . '";';
echo '  var defaultTitleNewRecording = "' . get_string('defaultTitleNewRecording', 'languagelab') . '";';
echo '  var errorTitle = "' . get_string('errorTitle', 'languagelab') . '";';
echo '  var recordingRequired = "' . get_string('recordingRequired', 'languagelab') . '";';
echo '  var reFeedBack = "' . get_string('reFeedBack', 'languagelab') . '";';
echo '  var gradeStudentWithRecordings = "' . get_string('gradeStudentWithRecordings', 'languagelab') . '";';
echo '  var defaultUserPicture = "' . $OUTPUT->pix_url('u/f2')->out(false) . '";';
//Set the player parameters
echo '  var rtmpserver = "' . '' . $CFG->languagelab_red5serverprotocol . '://' . $CFG->languagelab_red5server . '/' . $CFG->languagelab_red5serverfolder . '";';
echo '  var files_prefix = "' . $CFG->languagelab_folder . '/' . $id . '/' . $CFG->languagelab_prefix . '";';
echo '  var userid = ' .  $USER->id . ';';
echo '  var urlmasterTrack = "' . $mastertrack . '";';
echo '  var playeroptionsBtnOk = "' . get_string('playeroptionsBtnOk', 'languagelab') . '";';
echo '  var useGradebook = ' . (($languagelab->use_grade_book == 1) ? 'true' : 'false') . ';';
echo '  var urlDownload = "' . get_download_url($languagelab->video != 0) . '";';
echo '  var urlZipDownload = "' . get_download_zip_url() . '";';
echo '  var videoMode = ' . (($languagelab->video == 0) ? 'false' : 'true') . ';';
echo '</script>';

//Load the flash options menu
echo '
<div id="divPlayerOptions" title="' . get_string('titlePlayerOptions', 'languagelab') . '" style="position:absolute;top:-1000px;">
	<div id="divPlayerOptionsText" style="width: 400px;">
		' . get_string('playeroptionstxt1', 'languagelab') . '
		<ol>
			<li>' . get_string('playeroptionstxt2', 'languagelab', '<img src="' . $CFG->wwwroot . '/mod/languagelab/pix/privacy-ico.png"/>') . '</li>
			<li>' . get_string('playeroptionstxt3', 'languagelab', '<img src="' . $CFG->wwwroot . '/mod/languagelab/pix/allow-ico.png"/>') . '</li>
			<li>' . get_string('playeroptionstxt4', 'languagelab', '<img src="' . $CFG->wwwroot . '/mod/languagelab/pix/check-ico.png"/>') . '</li>
			<li>' . get_string('playeroptionstxt5', 'languagelab') . '</li>
			<li>' . get_string('playeroptionstxt6', 'languagelab') . '</li>
		</ol>
	</div>
	<div id="divPlayerOptionsObj" style="text-align:center;">
		<object type="application/x-shockwave-flash" data="flash/PlayerOptions.swf" width="250" height="160" name="playerOptions" id="playerOptions">
			<param name="allowScriptAccess" value="always" />
			<param name="allowFullScreen" value="true" />
			<param name="wmode" value="window">
			<param name="movie" value="flash/PlayerOptions.swf" />
			<param name="quality" value="high" />
		</object>
	</div>
	
	<div style="clear:both;"></div>
</div>       
';

//TEACHER VIEW
if (has_capability('mod/languagelab:teacherview', $context, null, true))
{

    echo '<style>';
    echo '#descrLabLang{width:760px;}';
    echo '</style>';

    echo '<script type="text/javascript">';
    echo '  var teacherMode = true;';
    echo '  var urlHistory = "teacher.recordings.php";';
    echo '</script>';

    $idPlayer = 'playerRecorderStudent';
    $idPlayerFeedback = 'playerRecorderTeacher';


    echo '<div id="dialogInfo" title="">';
    echo '</div>';

    echo '<div id="languageLabTeacher">';
    echo '  <div class="blockNewRecording">';
    echo '      <button id="micConfig" class="ui-corner-all">' . get_string('micConfig', 'languagelab') . '</button>';
    echo '      <button id="classMonitor" class="ui-corner-all" title="' . get_string('classmonitor_help', 'languagelab') . '" onclick="window.location=\'' . $CFG->wwwroot . '/mod/languagelab/classmonitor.php?id=' . $id . '\'">';
    echo '      ' . get_string('classmonitor', 'languagelab');
    echo '      </button>';
    echo '  </div>';
    echo '  <div id="connectionScreen" class="overlay-screen ui-widget-overlay"><img src="pix/ajax-loader.gif" />&nbsp;Connecting to server ...</div>';
    echo '  <div id="overlay" class="overlay-screen ui-widget-overlay" style="display: none;"></div>';
    echo '  <div id="no-cam" class="overlay-screen" style="display: none;"><img src="pix/missing-camera-ico.png" />&nbsp;' . get_string('error_missing_camera', 'languagelab') . '</div>';
    echo '  <div id="no-mic" class="overlay-screen" style="display: none;"><img src="pix/missing-microphone-ico.png" />&nbsp;' . get_string('error_missing_microphone', 'languagelab') . '</div>';

    //RECORDINGS
    echo '  <div class="blockRecordings">';
    echo '      <div class="blockHistory ui-corner-all">';
    echo '          <h3 class="recordingsHistory ui-widget-header ui-corner-all">' . get_string('recordingsHistory', 'languagelab');
    echo '              <img id="refreshHistory" src="pix/refresh-ico.png" alt="' . get_string('refresh', 'languagelab') . '" title="' . get_string('refresh', 'languagelab') . '"/>';
    echo '          </h3>';
    echo '          <div class="search">';
    echo '              ' . get_string('search', 'languagelab') . '<input type="text" name="searchRecordings" id="searchRecordings" class="ui-corner-all" size="15" />';
    echo '          </div>';
    echo '          <div id="recordings" class="ui-corner-all">';
    echo '          </div>';
    echo '          <button id="deleteRecordings" class="btnDisabled ui-corner-all" title="' . get_string('deleteRecord_help', 'languagelab') . '" disabled="disabled">';
    echo '          ' . get_string('deleteRecord', 'languagelab');
    echo '          </button>';
    echo '          <button id="downloadRecording" class="btnDisabled ui-corner-all" title="' . get_string('downloadRecord_help', 'languagelab') . '" disabled="disabled">';
    echo '          ' . get_string('downloadRecord', 'languagelab');
    echo '          </button>';
    echo '      </div>';
    echo '  </div>'; //END blockRecordings

    echo '  <div class="blockSeparator">';
    echo '  </div>'; //END blockSeparator
    //GRADES
    echo '  <div class="blockGrade ui-corner-all">';
    echo '      <div id="gradeInactif" class="ui-widget-overlay ui-corner-all"><br/><br/><br/>' . get_string('enableGradebook', 'languagelab') . '</div>';
    echo '      <h3 class="recordingsHistory ui-widget-header ui-corner-all">' . get_string('notesCorrection', 'languagelab');
    echo '      </h3>';
    echo '      <div id="gradeField">&nbsp;' . get_string('grade', 'languagelab');
    echo '          <input type="text" id="gradeInput" value="0" name="gradeInput" readonly="readonly" autocomplete="off"/>';
    echo '          <div id="gradeSlider"></div>';
    echo '      </div>'; //END gradeField
    echo '      <div id="gradePrivateNote">';
    echo '          ' . get_string('privateNotes', 'languagelab') . '<br/>';
    echo '          <textarea name="privateNote" id="privateNote" autocomplete="off" class="ui-corner-all"></textarea><br/>';
    echo '          <button id="submitGrade" title="' . get_string('submitGrade_help', 'languagelab') . '"  class="btnDisabled ui-corner-all" disabled="disabled">';
    echo '          ' . get_string('submitGrade', 'languagelab');
    echo '          </button>';
    echo '          <img id="ajaxSubmitGrade" style="display:none;" src="pix/throbber.gif" alt="Loading..."/>';
    echo '      </div>'; //END gradePrivateNote
    echo '  </div>'; //END blockGrade

    echo '  <div class="clearfix"></div>';


    //STUDENT
    echo '  <div class="blockStudentRecording ui-corner-all">';
    echo '      <h3 class="titleStudentRecording ui-widget-header ui-corner-all">' . get_string('student_recording', 'languagelab') . '</h3>';
    echo '          <div id="avatarStudent">';
    echo '              <img id="imgStudent" src="' . $OUTPUT->pix_url('u/f2')->out(false). '"/>';
    echo '              <span id="recordAgoStudent"></span></br>';
    echo '              <span id="nameStudent"></span>';
    echo '          </div>'; //END avatarStudent
    echo '          <div id="avatarTeacher">';
    echo '              <img id="imgTeacher" src="' . $OUTPUT->pix_url('u/f2')->out(false) . '"/>';
    echo '              <span id="recordAgoTeacher"></span></br>';
    echo '              <span id="nameTeacher"></span>';
    echo '          </div>'; //END avatarTeacher

    echo '          <div class="clearfix">';
    echo '          </div>';
    //STUDENT RECORDING
    echo '  <div class="blockCurrentRecording ui-corner-all">';
    echo '      <h3 class="titleRecording ui-widget-header ui-corner-all">' . get_string('titleRecording', 'languagelab') . '</h3>';
    echo '      <span class="lblTitleRecording">' . get_string('recordingTitle', 'languagelab') . '</span>';
    echo '      <span class="inputRecording"><input type="text" name="titleRecording" id="titleRecording" class="ui-corner-all" autocomplete="off" readonly="readonly" /></span>';
    echo '      <div class="clearfix"></div>';
    echo '      <div class="player ' . $classVideo . '">';
    echo '
        <object type="application/x-shockwave-flash" data="flash/PlayerRecorder.swf?idHTML=' . $idPlayer . '" width="350" height="' . $playerHeight . '" name="' . $idPlayer . '" id="' . $idPlayer . '" style="outline: none;" >
            <param name="movie" value="flash/PlayerRecorder.swf" />
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="true" />
            <param name="wmode" value="transparent"/> 
            <param name="quality" value="high" />
        </object>';
    echo '      </div>'; // END player
    echo '      <div class="description">';
    echo '          <div class="lblDescriptionRecording">' . get_string('description', 'languagelab') . '</div>';
    echo '          <textarea name="descriptionRecording" id="descriptionRecording" class="ui-corner-all" autocomplete="off" readonly="readonly"></textarea><br/>';
    echo '          <button style="visibility:hidden;" class="ui-corner-all" id="submitRecording" title="' . get_string('submitRecord_help', 'languagelab') . '">';
    echo '          ' . get_string('submitRecord', 'languagelab');
    echo '          </button>';
    echo '      </div>';
    echo '  </div>'; //END blockCurrentRecording STUDENTRECORDING

    echo '  <div class="blockSeparator">';
    echo '  </div>'; //END blockSeparator
    //FEEDBACK
    echo '  <div class="blockCurrentRecording ui-corner-all">';
    echo '      <h3 class="titleRecording ui-widget-header ui-corner-all">' . get_string('feedback', 'languagelab') . '</h3>';
    echo '      <span class="lblTitleRecording">' . get_string('recordingTitle', 'languagelab') . '</span>';
    echo '      <span class="inputRecording"><input type="text" name="titleFeedback" id="titleFeedback" class="ui-corner-all" autocomplete="off" readonly="readonly"/></span>';
    echo '      <div class="clearfix"></div>';
    echo '      <div class="player ' . $classVideo . '">';
    echo '
        <object type="application/x-shockwave-flash" data="flash/PlayerRecorder.swf?idHTML=' . $idPlayerFeedback . '" width="350" height="' . $playerHeight . '" name="' . $idPlayerFeedback . '" id="' . $idPlayerFeedback . '" style="outline: none;" >
            <param name="movie" value="flash/PlayerRecorder.swf" />
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="true" />
            <param name="wmode" value="transparent"/> 
            <param name="quality" value="high" />
        </object>';
    echo '      </div>'; // END player
    echo '      <div class="description">';
    echo '          <div class="lblDescriptionRecording">' . get_string('description', 'languagelab') . '</div>';
    echo '          <textarea name="descriptionFeedback" id="descriptionFeedback" class="ui-corner-all" autocomplete="off" readonly="readonly"></textarea><br/>';
    echo '          <button id="submitFeedback" title="' . get_string('submitRecord_help', 'languagelab') . '" disabled="disabled" class="btnDisabled ui-corner-all">';
    echo '          ' . get_string('submitRecord', 'languagelab');
    echo '          </button>';
    echo '          <img id="ajaxSubmitFeedback" style="display:none;" src="pix/throbber.gif" alt="Loading..."/>';
    echo '      </div>';
    echo '  </div>'; //END blockCurrentRecording FEEDBACK

    echo '  </div>'; //END blockStudentRecording


    echo '  <div class="clearfix"></div>';
    //Add buttons to go the previous or next language lab
    if ($languagelab->prev_next_lab)
    {
        $prevurl = languagelab_get_previous_next_lab_url($id, true);
        $nexturl = languagelab_get_previous_next_lab_url($id, false);
        if ($prevurl != '')
        {
            echo '      <button id="LLprevious" class="ui-corner-all" title="' . get_string('LLprevious_help', 'languagelab') . '" onclick="window.location.href=\'' . $prevurl . '\'">';
            echo '      ' . get_string('LLprevious', 'languagelab');
            echo '       </button>';
        }
        if ($nexturl != '')
        {
            echo '      <button id="LLnext" class="ui-corner-all" title="' . get_string('LLnext_help', 'languagelab') . '" onclick="window.location.href=\'' . $nexturl . '\'">';
            echo '      ' . get_string('LLnext', 'languagelab');
            echo '       </button>';
        }
    }
    echo '</div>'; //END languageLabTeacher
}
else
{

    $nbRecords = count($DB->get_records('languagelab_submissions', array('languagelab' => $languagelab->id, 'userid' => $USER->id)));

    $student_grade = $DB->get_record('languagelab_student_eval', array('userid' => $USER->id, 'languagelab' => $languagelab->id));

    echo '<style>';
    echo '#descrLabLang{width:650px;}';
    echo '</style>';

    echo '<script type="text/javascript">';
    echo '  var urlHistory = "student.recordings.php";';
    echo '  var urlLive = "ajax.live.php";';
    echo '</script>';

    $idPlayer = 'playerRecorderStudent';

    echo '<div id="dialogInfo" title="">';
    echo '</div>';

    echo '<div id="languageLabStudent">';

    if ($student_grade != null && $student_grade->grade != 0)
    {
        echo '<div id="gradeStudent" style="background-image:url(' . $CFG->wwwroot . '/mod/languagelab/pix/grades/grade-' . $student_grade->grade . '.png)"></div>';
    }
    echo '  <div class="blockNewRecording">';
    echo '      <button id="micConfig" class="ui-corner-all">' . get_string('micConfig', 'languagelab') . '</button>';
    if ($available)
    {
        $btnDisabled = '';
        if ($languagelab->attempts == 1 && $nbRecords > 0)
        {
            $btnDisabled = 'class="btnDisabled" disabled="disabled" ';
        }
        echo '      <button id="newRecording" class="ui-corner-all" ' . $btnDisabled . '>' . get_string('newRecording', 'languagelab') . '</button>';
    }
    echo '      <button id="raiseHand" class="ui-corner-all" title="' . get_string('raiseHand_help', 'languagelab') . '">';
    echo '      ' . get_string('raiseHand', 'languagelab');
    echo '      </button>';
    echo '  </div>';
    echo '  <div id="connectionScreen" class="overlay-screen ui-widget-overlay"><img src="pix/ajax-loader.gif" />&nbsp;' . get_string('connectiongServer', 'languagelab') . '</div>';
    echo '  <div id="overlay" class="overlay-screen ui-widget-overlay" style="display: none;"></div>';
    echo '  <div id="no-cam" class="overlay-screen" style="display: none;"><img src="pix/missing-camera-ico.png" />&nbsp;' . get_string('error_missing_camera', 'languagelab') . '</div>';
    echo '  <div id="no-mic" class="overlay-screen" style="display: none;"><img src="pix/missing-microphone-ico.png" />&nbsp;' . get_string('error_missing_microphone', 'languagelab') . '</div>';

    echo '  <div class="blockCurrentRecording ui-corner-all">';
    echo '      <h3 class="titleRecording ui-widget-header ui-corner-all">' . get_string('titleRecording', 'languagelab');
    echo '          <img id="listened" style="display:none;" src="pix/ear-ico.png" alt="' . get_string('listened', 'languagelab') . '" title="' . get_string('listened', 'languagelab') . '"/>';
    echo '          <img id="thumbsup" style="display:none;" src="pix/thumbsup2-ico.png" alt="' . get_string('thumbsup_student', 'languagelab') . '" title="' . get_string('thumbsup_student', 'languagelab') . '"/>';
    echo '      </h3>';
    echo '      <span class="lblTitleRecording">' . get_string('recordingTitle', 'languagelab') . '</span>';
    echo '      <span class="inputRecording"><input type="text" name="titleRecording" id="titleRecording" class="ui-corner-all" autocomplete="off" readonly="readonly" /></span>';
    echo '      <div class="clearfix"></div>';
    echo '      <div class="player ' . $classVideo . '">';
    echo '
        <object type="application/x-shockwave-flash" data="flash/PlayerRecorder.swf?idHTML=' . $idPlayer . '" width="350" height="' . $playerHeight . '" name="' . $idPlayer . '" id="' . $idPlayer . '" style="outline: none;" >
            <param name="movie" value="flash/PlayerRecorder.swf" />
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="true" />
            <param name="wmode" value="transparent"/> 
            <param name="quality" value="high" />
        </object>';
    echo '      </div>'; // END player

    echo '      <div class="description">';
    echo '          <div class="lblDescriptionRecording">' . get_string('description', 'languagelab') . '</div>';
    echo '          <textarea name="descriptionRecording" id="descriptionRecording" class="ui-corner-all" autocomplete="off" readonly="readonly"></textarea><br/>';
    echo '          <button id="submitRecording" title="' . get_string('submitRecord_help', 'languagelab') . '" class="btnDisabled ui-corner-all" disabled="disabled">';
    echo '          ' . get_string('submitRecord', 'languagelab');
    echo '          </button>';
    echo '          <img id="ajaxSubmitRecording" style="display:none;" src="pix/throbber.gif" alt="Loading..."/>';
    echo '      </div>';
    echo '  </div>'; //END blockCurrentRecording

    echo '  <div class="blockHistory ui-corner-all">';
    echo '      <h3 class="recordingsHistory ui-widget-header ui-corner-all">' . get_string('recordingsHistory', 'languagelab');
    echo '          <img id="refreshHistory" src="pix/refresh-ico.png" alt="' . get_string('refresh', 'languagelab') . '" title="' . get_string('refresh', 'languagelab') . '"/>';
    echo '      </h3>';
    echo '      <div class="search">';
    echo '          ' . get_string('search', 'languagelab') . '<input type="text" name="searchRecordings" id="searchRecordings" class="ui-corner-all" size="15" />';
    echo '      </div>';
    if ($languagelab->video != 0)
    {
        echo '      <div id="recordings" class="ui-corner-all videoMode">';
    }
    else
    {
        echo '      <div id="recordings" class="ui-corner-all">';
    }
    echo '      </div>';
    if ($available)
    {

        echo '      <button id="deleteRecordings" class="btnDisabled ui-corner-all" title="' . get_string('deleteRecord_help', 'languagelab') . '" disabled="disabled">';
        echo '      ' . get_string('deleteRecord', 'languagelab');
        echo '      </button>';
        echo '      <button id="downloadRecording" class="btnDisabled ui-corner-all" title="' . get_string('downloadRecord_help', 'languagelab') . '" disabled="disabled">';
        echo '      ' . get_string('downloadRecord', 'languagelab');
        echo '       </button>';
    }
    else
    {
        echo '      <button id="deleteRecordings" class="btnDisabled ui-corner-all" style="visibility:hidden;" disabled="disabled">.</button>';
    }
    echo '  </div>'; // END blockHistory

    echo '  <div class="clearfix">';
    echo '  </div>';

    //Add buttons to go the previous or next language lab
    if ($languagelab->prev_next_lab)
    {
        $prevurl = languagelab_get_previous_next_lab_url($id, true);
        $nexturl = languagelab_get_previous_next_lab_url($id, false);
        if ($prevurl != '')
        {
            echo '      <button id="LLprevious" class="ui-corner-all" title="' . get_string('LLprevious_help', 'languagelab') . '" onclick="window.location.href=\'' . $prevurl . '\'">';
            echo '      ' . get_string('LLprevious', 'languagelab');
            echo '       </button>';
        }
    }
    //Add a button to go back to the course.
    if ($languagelab->fullscreen_student)
    {
        $onclickgoback = 'window.location.href=\'' . course_get_url($course->id) . '\'';
        echo '      <button id="goBackCourse" class="btnGoBack ui-corner-all" title="' . get_string('goBackCourse_help', 'languagelab') . '" onclick="' . $onclickgoback . '">';
        echo '      ' . get_string('goBackCourse', 'languagelab');
        echo '       </button>';
    }
    //Add buttons to go the previous or next language lab
    if ($languagelab->prev_next_lab)
    {
        if ($nexturl != '')
        {
            echo '      <button id="LLnext" class="ui-corner-all" title="' . get_string('LLnext_help', 'languagelab') . '" onclick="window.location.href=\'' . $nexturl . '\'">';
            echo '      ' . get_string('LLnext', 'languagelab');
            echo '       </button>';
        }
    }

    echo '</div>'; // END languageLabStudent
    //We need to determine if activity is available for the times chosen by teacher
    $now = time();
    $available = $languagelab->timeavailable < $now && ($now < $languagelab->timedue || !$languagelab->timedue);
    if (!$available)
    {

        //Enter the proper date/time or a text message if no date/time
        if (empty($languagelab->timedue))
        {
            $timedue = get_string('no_due_date', 'languagelab');
        }
        else
        {
            $timedue = userdate($languagelab->timedue);
        }

        if (empty($languagelab->timeavailable))
        {
            $timeavailable = get_string('no_available_date', 'languagelab');
        }
        else
        {
            $timeavailable = userdate($languagelab->timeavailable);
        }

        //Activity not availabe
        $OUTPUT->box(get_string('not_available', 'languagelab') . '<p>' . get_string('availabledate', 'languagelab') . $timeavailable . '<br>' . get_string('duedate', 'languagelab') . $timedue, 'languagelab_submit', 'languagelab_submit');
    }
}

echo '</div>';
echo $OUTPUT->box_end();

// Finish the page
echo $OUTPUT->footer();
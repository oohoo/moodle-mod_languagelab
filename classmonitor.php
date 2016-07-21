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
}
else
{
    error('You must specify a course_module ID or an instance ID');
}


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
$PAGE->requires->js('/mod/languagelab/js/flash_detect_min.js', true);
$PAGE->requires->js('/mod/languagelab/js/languagelab-classmonitor.js', true);

$PAGE->requires->css('/mod/languagelab/style-classmonitor.css');

//Only for Moodle < 2.7
if(!function_exists('moodle_major_version') || moodle_major_version() < '2.7')
{
    add_to_log($course->id, 'languagelab', 'view', "classmonitor.php?id=$cm->id", $languagelab->name, $cm->id);
}
else
{
    //TODO Log for Moodle 2.7+
}

/// Print the page header

$PAGE->set_url('/mod/languagelab/classmonitor.php', array('id' => $cm->id));
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
echo '<script type="text/javascript">
    var playerRecorders = [];
    var playerOptions;
    var userLiveURI;
    var userRecordURI;
    

    </script>';

if (has_capability('mod/languagelab:teacherview', $context, null, true))
{
    echo '<div align=\'center\' style="position:relative;padding-right:10px;">';

    //************************Get teacher information*****************************************
    $user = $DB->get_record("user", array("id" => $USER->id));
    $userpictureurl = $OUTPUT->user_picture($user, array('courseid' => $course->id, 'link' => false));
    //create an array from the image tag
    $newuserpictureurl = explode(' ', $userpictureurl);
    //Get the link info from array row 1 and remove src="
    $userpicture = str_replace('src="', '', $newuserpictureurl[1]);
    //remove last double quotation marks;
    $userpicture = str_replace('"', '', $userpicture);
    //************************End teacher information*******************************
    //****************Get students *************************************
    $students = get_enrolled_users($context, 'mod/languagelab:studentview');
    //**************************************************************************
    //************************Player options*******************************
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
    //************************End Player options*******************************

    $idPlayer = 'playerRecorderClassMonitor';

    echo '<script type="text/javascript">';
    echo '  var activityid = ' . $id . ';';
    echo '  var checksum = "";';
    echo '  var stealthActivated = false;';
    echo '  var stealthTextActive = "' . get_string('stealthActive', 'languagelab') . '";';
    echo '  var stealthTextInactive = "' . get_string('stealthInactive', 'languagelab') . '";';
    echo '  var playeroptionsBtnOk = "' . get_string('playeroptionsBtnOk', 'languagelab') . '";';
    echo '  var secondsRefreshMonitor = ' . $CFG->languagelab_secondsRefreshClassmonitor . ';';
    echo '  var errorTitle = "' . get_string('errorTitle', 'languagelab') . '";';
    echo '  var cancel = "' . get_string('cancel', 'languagelab') . '";';
    echo '  var defaultUserPicture = "' . $CFG->wwwroot . '/theme/image.php?image=u/f1";';
    echo '  var urlMonitor = "' . $CFG->wwwroot . '/mod/languagelab/ajax.classmonitor.php";';
    //Set the player parameters
    echo '  var rtmpserver = "' . '' . $CFG->languagelab_red5serverprotocol . '://' . $CFG->languagelab_red5server . '/' . $CFG->languagelab_red5serverfolder . '";';
    echo '  var files_prefix = "' . $CFG->languagelab_folder . '/' . $id . '/' . $CFG->languagelab_prefix . '";';
    echo '  var textLoadingConnectClient = \'<div style="width: 100%;text-align:center;"><img src="pix/ajax-loader2.gif" /><br/>' . get_string('connectClient', 'languagelab') . '</div>\';';
    echo '  var textLoadingDisconnectClient = \'<div style="width: 100%;text-align:center;"><img src="pix/ajax-loader2.gif" /><br/>' . get_string('disconnectClient', 'languagelab') . '</div>\';';
    echo '</script>';

    //************************Player recorder*******************************
    echo '<div class="player" style="position:absolute; top:-1000px;">';
    echo '
    <object type="application/x-shockwave-flash" data="flash/PlayerRecorder.swf?idHTML=' . $idPlayer . '" width="350" height="45" name="' . $idPlayer . '" id="' . $idPlayer . '" style="outline: none;" >
        <param name="movie" value="flash/PlayerRecorder.swf" />
        <param name="allowScriptAccess" value="always" />
        <param name="allowFullScreen" value="true" />
        <param name="wmode" value="transparent"/> 
        <param name="quality" value="high" />
    </object>';
    echo '</div>'; // END player
    //************************End Player recorder*******************************

    echo '<div id="dialogInfo" title="">';
    echo '</div>';

    echo '  <div id="connectionScreen" class="ui-widget-overlay"><img src="pix/ajax-loader.gif" />&nbsp;Connecting to server ...</div>';


    echo '<div id="buttonsMonitor"  class="ui-corner-all">';
    echo '  <span id="searchField"  class="ui-corner-all ui-widget-header" title="' . get_string('filterStudents_help', 'languagelab') . '" >';
    echo '          <input type="text" id="searchStudents" name="searchStudents" class="ui-corner-all" autocomplete="off" placeholder="' . get_string('filterStudents', 'languagelab') . '"/>';
    echo '  </span>';
    echo '  <button id="micConfig" class="ui-corner-all">' . get_string('micConfig', 'languagelab') . '</button>';
    echo '  <button id="stealth" class="ui-corner-all" title="' . get_string('stealthMode_help', 'languagelab') . '">' . get_string('stealth', 'languagelab') . ' "<span class="status">' . get_string('stealthInactive', 'languagelab') . '</span>"</button>';
    echo '  <button id="speakToClass" class="ui-corner-all" title="' . get_string('speakToClasshelp', 'languagelab') . '">' . get_string('speakToClass', 'languagelab') . '</button>';
    echo '  <button id="listRecordings" class="ui-corner-all" title="' . get_string('listRecordings_help', 'languagelab') . '" onclick="window.open(\'' . $CFG->wwwroot . '/mod/languagelab/view.php?id=' . $id . '&embed=true\',\'listRecordings\',\'status=0,width=800,resizable=1,scrollbars=1\');return false;">' . get_string('listRecordings', 'languagelab') . '</button>';
    echo '  </div>';

    //BLOCK STUDENT ONLINE
    echo '<div id="blockStudentsOnline" class="ui-corner-all">';
    echo '  <h3 class="titleStudentsOnline ui-widget-header ui-corner-all">' . get_string('studentsOnline', 'languagelab');
    echo '  </h3>';
    echo '  <div id="listStudentsOnline" class="ui-corner-all">';
    echo '      <div class="clearfix"></div>';
    echo '  </div>'; //END listStudentsOnline
    echo '</div>'; //END blockStudentsOnline
    //BLOCK STUDENT OFFLINE

    echo '<div id="blockStudentsOffline" class="ui-corner-all">';
    echo '  <h3 class="titleStudentsOffline ui-widget-header ui-corner-all">' . get_string('studentsOffline', 'languagelab');
    echo '  </h3>';
    echo '  <div id="listStudentsOffline" class="ui-corner-all">';
    foreach ($students as $student)
    {
        $studentinfo = $DB->get_record("user", array("id" => $student->id));
        $studentpictureurl = $OUTPUT->user_picture($studentinfo, array('courseid' => $course->id, 'link' => false, 'size' => 100));
        //create an array from the image tag
        $newstudentpictureurl = explode(' ', $studentpictureurl);
        //Get the link info from array row 1 and remove src="
        $studentpicture = str_replace('src="', '', $newstudentpictureurl[1]);
        //remove last double quotation marks;
        $studentpicture = str_replace('"', '', $studentpicture);

        echo '      <div id="student_' . $student->id . '" class="classMonitorStudent ui-corner-all">';
        echo '          <img src="' . $studentpicture . '"/ class="ui-corner-all"><br />';
        echo '          <span class="studentName">' . fullname($student) . '</span>';
        echo '          <div class="menuStudent ui-widget-header ui-corner-all" style="display:none;">';
        echo '              <button class="talkToStudent ui-corner-all" title="' . get_string('talkToStudent_help', 'languagelab') . '">&nbsp;</button>';
        echo '              <button class="thumbsUp ui-corner-all" title="' . get_string('thumbsUp_help', 'languagelab') . '">&nbsp;</button>';
        echo '          </div>';
        echo '      </div>';
    }
    echo '      <div class="clearfix"></div>';
    echo '  </div>'; //END listStudentsOffline
    echo '</div>'; //END blockStudentsOffline


    echo '<br/><br/>';
    echo '</div>'; //END main block
}
else
{
    error('You don\'t have permission to access to this page');
}
echo $OUTPUT->box_end();

// Finish the page
echo $OUTPUT->footer();
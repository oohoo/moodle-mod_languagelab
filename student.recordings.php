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
session_cache_limiter('nocache'); //Needed for XML to load with IE
//header("Cache-Control: no-cache"); //Prevent caching issues with MSIE
require_once("../../config.php");
require_once("lib.php");
require_once("locallib.php");

//We need to determine if activity is available for the times chosen by teacher


$id = optional_param('activity_id', 0, PARAM_INT); // Course Module ID, or
$l = optional_param('l', 0, PARAM_INT);  // languagelab ID

$selecteduser = optional_param('selecteduser', 0, PARAM_INT);
$selectedelem = optional_param('selectedelem', 0, PARAM_INT);

global $CFG, $DB;

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

//Replace get_context_instance by the class for moodle 2.6+
if(class_exists('context_module'))
{
    $context = context_module::instance($cm->id);
}
else
{
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
}

//************************Get teacher information*****************************************
require_login($course, true, $cm); //Needed to gather proper course language used

$student = $DB->get_record("user", array("id" => $USER->id));
$studentid = $USER->id;
$studentname = fullname($student);
$userpictureurl = $OUTPUT->user_picture($student, array('courseid' => $course->id, 'link' => false)); 
//create an array from the image tag
$newuserpictureurl = explode(' ', $userpictureurl);
//Get the link info from array row 1 and remove src="
$studentpicture = str_replace('src="', '', $newuserpictureurl[1]);
//remove last double quotation marks;
$studentpicture = str_replace('"', '', $studentpicture);
//***************************End Teacher Information**************************************
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
//****************Get students recording*************************************
$recordings = $DB->get_records('languagelab_submissions', array('userid' => $studentid, 'languagelab' => $languagelab->id));
//**************************************************************************
//Get student grade
$student_grade = $DB->get_record('languagelab_student_eval', array('userid' => $studentid, 'languagelab' => $languagelab->id));

// if attempts is true, it must equal 0
if ($languagelab_params->attempts == 1)
{
    $attempts = 0;
}
else
{
    $attempts = 1;
}
//**********JSON OUTPUT******************

$json = array();
$checksumData = array();
foreach ($recordings as $recording)
{

    $checksumData[] = array('id' => $recording->id, 'timemodified' => $recording->timemodified);
    $elem = new stdClass();

    $elem->data = new stdClass();
    $elem->data->title = str_replace('@@', "'", $recording->label); //.'<span class="delRecord>">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
    $elem->data->attr = new stdClass();
    $elem->data->attr->href = '#';
    $elem->data->icon = 'record';

    $elem->metadata = new stdClass();
    $elem->metadata->type = "record";
    $elem->metadata->title = $elem->data->title;
    $elem->metadata->recURI = $recording->path;
    $elem->metadata->mastertrack = $mastertrack;
    $elem->metadata->lastUpdate = formatTimeSince($recording->timemodified);
    $elem->metadata->author = $studentname;
    $elem->metadata->portrait = $studentpicture;
    $elem->metadata->tMessage = $recording->message;
    $elem->metadata->recordingid = $recording->id;
    $elem->metadata->grade = $student_grade;
    $elem->metadata->studentid = $studentid;
    $elem->metadata->downloadName = format_name_download($studentname . '_' . $elem->data->title . '_' . $recording->id);

    //If selected or if only one record per student select this one by default
    if ($selectedelem != 0 && $selectedelem == $recording->id || $languagelab->attempts == 1 && $selectedelem == 0)
    {
        $elem->metadata->selected = true;
    }
    else
    {
        $elem->metadata->selected = false;
    }

    $parentnode = array("parentnode" => $recording->path, "languagelab" => $languagelab->id);
    $elem->children = array();
    $childnodes = $DB->get_records('languagelab_submissions', $parentnode);
    foreach ($childnodes as $childnode)
    {

        $checksumData[] = array('id' => $childnode->id, 'timemodified' => $childnode->timemodified);

        $teacher = $DB->get_record("user", array("id" => $childnode->userid));
        $teacherpictureurl = $OUTPUT->user_picture($teacher, array('courseid' => $course->id, 'link' => false)); //$CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
        //create an array from the image tag
        $newteacherpictureurl = explode(' ', $teacherpictureurl);
        //Get the link info from array row 1 and remove src="
        $teacherpicture = str_replace('src="', '', $newteacherpictureurl[1]);
        //remove last double quotation marks;
        $teacherpicture = str_replace('"', '', $teacherpicture);

        $child = new stdClass();
        $child->data = new stdClass();
        $child->data->title = str_replace('@@', "'", $childnode->label); //.'<span class="delRecord>">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        $child->data->attr = new stdClass();
        $child->data->attr->href = '#';
        $child->data->icon = 'feedback';

        $child->metadata = new stdClass();
        $child->metadata->type = "feedback";
        $child->metadata->title = $child->data->title;
        $child->metadata->recURI = $childnode->path;
        $child->metadata->mastertrack = '';
        $child->metadata->lastUpdate = formatTimeSince($childnode->timemodified);
        $child->metadata->author = fullname($teacher);
        $child->metadata->portrait = $teacherpicture;
        $child->metadata->tMessage = $childnode->message;
        $child->metadata->recordingid = $childnode->id;
        $child->metadata->studentid = $studentid;
        $child->metadata->downloadName = format_name_download(fullname($teacher) . '_' . $child->data->title . '_' . $recording->id);
        if ($selectedelem != 0 && $selectedelem == $childnode->id)
        {
            $child->metadata->selected = true;
        }
        else
        {
            $child->metadata->selected = false;
        }

        $elem->children[] = $child;
    }
    $json[] = $elem;
}
$obj = new stdClass();
$obj->checksum = md5(json_encode($checksumData));
$obj->json = $json;
echo json_encode($obj);
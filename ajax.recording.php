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

//************************Get language activity params*******************************
$languagelab_params = $DB->get_record('languagelab', array('id' => $languagelab->id));

$now = time();
$available = $languagelab_params->timeavailable < $now && ($now < $languagelab_params->timedue || !$languagelab_params->timedue);
//************************End language activity params******************************

require_login($course, true, $cm);

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

$userid = $USER->id;

$add = optional_param('add', false, PARAM_BOOL);
$upd = optional_param('upd', false, PARAM_BOOL);
$del = optional_param('del', false, PARAM_BOOL);
$grade = optional_param('grade', false, PARAM_BOOL);

$result = new stdClass();
$result->success = true;
$result->message = '';

if ($add)
{
    //GET THE POST DATA
    $title = optional_param('title', '', PARAM_TEXT);
    $message = optional_param('message', '', PARAM_TEXT);
    $path = optional_param('path', '', PARAM_TEXT);

    $parentsubmission = optional_param('submission', 0, PARAM_INT);

    if ($parentsubmission != 0)
    {
        if (has_capability('mod/languagelab:teacherview', $context, null, true))
        {

            if ($record = $DB->get_record('languagelab_submissions', array('id' => $parentsubmission)))
            {
                $save_recording = new object();
                $save_recording->languagelab = $languagelab->id;
                $save_recording->userid = $userid;
                $save_recording->groupid = $groupid;
                $save_recording->path = $path;
                $save_recording->label = $title;
                $save_recording->message = $message;
                $save_recording->parentnode = $record->path;
                $save_recording->timecreated = time();
                $save_recording->timemodified = time();

                //If not Mode video. CONVERT IN MP3
                if ($languagelab->video == 0)
                {
                    //If conversion successful change the path, else, keep the flv file
                    if (convert_recording($path, 'mp3') == 1)
                    {
                        $save_recording->path = 'mp3:' . $save_recording->path;
                    }
                }
                else
                {
                    //If conversion successful change the path, else, keep the flv file
                    //MP4 is not compatible with RED5 0.8 for now (and other version)
                    /*if (convert_recording($path, 'mp4') == 1)
                    {
                        $save_recording->path = 'mp4:' . $save_recording->path;
                    }*/
                }

                $result->success = $DB->insert_record('languagelab_submissions', $save_recording);
            }
            else
            {
                //RETURN ERROR NOT GOOD RIGHTS
                $result->success = false;
                $result->message = get_string('error_insert_feedback_permission', 'languagelab');
            }
        }
        else
        {
            //RETURN ELEM NOT EXISTS
            $result->success = false;
            $result->message = get_string('error_insert_feedback_parent_notexists', 'languagelab');
        }
    }
    else
    {
        $save_recording = new object();
        $save_recording->languagelab = $languagelab->id;
        $save_recording->userid = $userid;
        $save_recording->groupid = $groupid;
        $save_recording->path = $path;
        $save_recording->label = $title;
        $save_recording->message = $message;
        $save_recording->parentnode = '';
        $save_recording->timecreated = time();
        $save_recording->timemodified = time();

        //If not Mode video. CONVERT IN MP3
        if ($languagelab->video == 0)
        {
            //If conversion successful change the path, else, keep the flv file
            if (convert_recording($path, 'mp3') == 1)
            {
                $save_recording->path = 'mp3:' . $save_recording->path;
            }
        }
        else
        {
            //If conversion successful change the path, else, keep the flv file
            //MP4 is not compatible with RED5 0.8 for now (and other version)
            /*
            if (convert_recording($path, 'mp4') == 1)
            {
                $save_recording->path = 'mp4:' . $save_recording->path;
            }*/
        }

        //If the activity is available
        if ($available)
        {
            //If the user can create an other record
            if ($languagelab->attempts == 0 || ($languagelab->attempts == 1 && count($DB->get_records('languagelab_submissions', array('languagelab' => $languagelab->id, 'userid' => $USER->id))) == 0))
            {
                $result->success = $DB->insert_record('languagelab_submissions', $save_recording);


                $grades = $DB->get_records('languagelab_student_eval', array('languagelab' => $languagelab->id, 'userid' => $userid));

                //this will be used to create grade record in languagelab_student_eval
                //Only create one record for the grade. Multiple grade records would break the system.
                if (count($grades) == 0)
                { //Will only create the record once
                    $save_grade = new object();
                    $save_grade->languagelab = $languagelab->id;
                    $save_grade->userid = $userid;
                    $DB->insert_record('languagelab_student_eval', $save_grade);
                }

                if ($result->success === false)
                {
                    $result->message = get_string('error_record_save', 'languagelab');
                }
            }
            else
            {
                $result->success = false;
                $result->message = get_string('error_user_max_attempts', 'languagelab');
            }
        }
        else //The activity is no longer available
        {
            $result->success = false;
            $result->message = get_string('error_activity_not_available', 'languagelab');
        }
    }
}
else if ($del)
{
    $recordingid = required_param('recordingid', PARAM_INT);
    if ($elem = $DB->get_record('languagelab_submissions', array('id' => $recordingid)))
    {
        $canDelete = false;
        //If teacher, can delete all records, else only his own
        if (has_capability('mod/languagelab:teacherview', $context, null, true))
        {
            $canDelete = true;
        }
        else if ($elem->userid == $userid)
        {
            if ($available)
            {
                $canDelete = true;
            }
            else
            {
                $canDelete = false;
            }
        }

        if ($canDelete)
        {
            //Only Delete children if the elem path exists
            if (count($elem->path) > 3)
            {
                $children = $DB->get_records('languagelab_submissions', array('parentnode' => $elem->path));
                foreach ($children as $child)
                {
                    //Delete the feedback file
                    delete_single_recording($child->id);
                    $DB->delete_records('languagelab_submissions', array('id' => $child->id));
                }
            }
            //Delete the main file
            echo delete_single_recording($elem->id);


            if (!$DB->delete_records('languagelab_submissions', array('id' => $elem->id)))
            {
                $result->success = false;
                $result->message = get_string('error_record_save', 'languagelab');
            }
        }
        else
        {
            if ($elem->userid == $userid && $available)
            {
                $result->success = false;
                $result->message = get_string('error_activity_not_available_delete', 'languagelab');
            }
            else
            {
                //RETURN ERROR NOT GOOD RIGHTS
                $result->success = false;
                $result->message = get_string('error_delete_permission', 'languagelab');
            }
        }
    }
    else
    {
        //RETURN ELEM NOT EXISTS
        $result->success = false;
        $result->message = get_string('error_delete_notexists', 'languagelab');
    }
}
else if ($grade)
{
    if (has_capability('mod/languagelab:teacherview', $context, null, true))
    {
        $studentid = required_param('studentid', PARAM_INT);
        $valgrade = required_param('grade', PARAM_INT);
        $correctionnotes = required_param('privateNotes', PARAM_TEXT);
        if ($grade = $DB->get_record('languagelab_student_eval', array('languagelab' => $languagelab->id, 'userid' => $studentid)))
        {
            $grade->grade = $valgrade;
            $grade->correctionnotes = $correctionnotes;
            if ($grade->timecreated == 0)
            {
                $grade->timecreated = time();
            }
            $grade->timemarked = time();
            $grade->timemodified = time();
            $res = $DB->update_record('languagelab_student_eval', $grade);

            if (!$res)
            {
                //RETURN ERROR grade not saved
                $result->success = false;
                $result->message = get_string('error_grade_notsaved', 'languagelab');
            }
            else
            {
                $grade->rawgrade = $grade->grade;
                //Update the gradebook;
                languagelab_grade_item_update($languagelab, $grade);
            }
        }
        else
        {
            //RETURN ERROR USER NOT EXISTS IN Activity
            $result->success = false;
            $result->message = get_string('error_grade_user_notexists', 'languagelab');
        }
    }
    else
    {
        //RETURN ERROR NOT GOOD RIGHTS
        $result->success = false;
        $result->message = get_string('error_grade_permission', 'languagelab');
    }
}
echo json_encode($result);
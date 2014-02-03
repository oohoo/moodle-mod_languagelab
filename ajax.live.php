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


$userid = $USER->id;

$event = optional_param('event', '', PARAM_TEXT);

if ($event == 'raise_hand')
{
    $teachers = get_enrolled_users($context, 'mod/languagelab:teacherview');

    $obj = new stdClass();
    $obj->languagelab = $languagelab->id;
    $obj->userid = 0;
    $obj->type = 'raise_hand';
    $obj->data = new stdClass();
    $obj->timecreated = time();

    $obj->data->studentid = $USER->id;

    $obj->data = json_encode($obj->data);

    foreach ($teachers as $teacher)
    {
        $obj->userid = $teacher->id;
        $DB->insert_record('languagelab_user_event', $obj);
    }

    $result = new stdClass();
    $result->success = true;
    $result->data = array();
}
else
{
    $liveURI = required_param('uri', PARAM_TEXT);


    //Get the older live or create a new one
    if ($user_live = $DB->get_record('languagelab_user_live', array('languagelab' => $languagelab->id, 'userid' => $userid)))
    {
        $user_live->live = $liveURI;
        $user_live->timemodified = time();
        $DB->update_record('languagelab_user_live', $user_live);
    }
    else
    {
        $user_live = new stdClass();
        $user_live->languagelab = $languagelab->id;
        $user_live->userid = $userid;
        $user_live->live = $liveURI;
        $user_live->timemodified = time();
        $DB->insert_record('languagelab_user_live', $user_live);
    }

    $result = new stdClass();
    $result->success = true;
    $result->data = array();
    $result->message = '';

    //Check if there is event for the user:
    $events = $DB->get_records('languagelab_user_event', array('languagelab' => $languagelab->id, 'userid' => $userid));

    if (count($events))
    {
        //Parse the events
        foreach ($events as $event)
        {
            $event->data = json_decode($event->data);

            if ($event->type == 'live_add' || $event->type == 'liveclass_add')
            {
                //************************Get teacher information*****************************************
                $teacher = $DB->get_record("user", array("id" => $event->data->teacher_userid));

                $teacherpictureurl = $OUTPUT->user_picture($teacher, array('courseid' => $course->id, 'link' => false, 'size' => 100));
                //create an array from the image tag
                $newteacherpictureurl = explode(' ', $teacherpictureurl);
                //Get the link info from array row 1 and remove src="
                $teacherpicture = str_replace('src="', '', $newteacherpictureurl[1]);
                //remove last double quotation marks;
                $teacherpicture = str_replace('"', '', $teacherpicture);
                //************************End teacher information*******************************

                if ($event->type == 'live_add')
                {
                    $event->data->message = get_string('teacher_student_speak', 'languagelab', '<img alt="." src="' . $teacherpicture . '"/> ' . fullname($teacher));
                    $event->data->title = get_string('teacher_student_speak_title', 'languagelab');
                }
                else
                {
                    $event->data->message = get_string('teacher_class_speak', 'languagelab', '<img alt="." src="' . $teacherpicture . '"/> ' . fullname($teacher));
                    $event->data->title = get_string('teacher_class_speak_title', 'languagelab');
                }
            }
            //Get the event
            $result->data[] = $event;
            //End delete it right away
            $DB->delete_records('languagelab_user_event', array('id' => $event->id));
        }
    }
}

echo json_encode($result);
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
//Get the potential event sent
$eventType = optional_param('event', '', PARAM_TEXT);

if (!has_capability('mod/languagelab:teacherview', $context, null, true))
{
    error('You don\'t have the privilege to access to this page');
}
else if ($eventType != '')
{
    $studentid = required_param('studentid', PARAM_INT);

    //Create a transaction to ensure the integrity of the data
    $transaction = $DB->start_delegated_transaction();

    $result = new stdClass();
    $result->success = true;
    $result->message = '';
    $result->data = new stdClass();
    $insertEvent = true;
    $obj = new stdClass();
    $obj->languagelab = $languagelab->id;
    $obj->userid = $studentid;
    $obj->type = $eventType;
    $obj->data = new stdClass();
    $obj->timecreated = time();

    $obj->data->teacher_userid = $USER->id;

    //Can do things before add them
    if ($eventType == 'live_add')
    {
        $teacherlive = required_param('teacherlive', PARAM_TEXT);

        $obj->data->uri = $teacherlive;
    }
    else if ($eventType == 'live_remove')
    {
        $teacherlive = required_param('teacherlive', PARAM_TEXT);

        $obj->data->uri = $teacherlive;

        //If the live_add already exists, delete it and don't insert the live remove
        if ($addLive = $DB->get_record_sql('SELECT * FROM {languagelab_user_event} WHERE languagelab = ? AND userid = ? AND type = ? AND data LIKE ?', array($languagelab->id, $studentid, 'live_add', '%' . $teacherlive . '%'))
        )
        {
            $DB->delete_records('languagelab_user_event', array('id' => $addLive->id));
            $insertEvent = false;
        }
    }
    if ($eventType == 'liveclass_add')
    {
        $teacherlive = required_param('teacherlive', PARAM_TEXT);

        $obj->data->uri = $teacherlive;
    }
    else if ($eventType == 'liveclass_remove')
    {
        $teacherlive = required_param('teacherlive', PARAM_TEXT);

        $obj->data->uri = $teacherlive;

        //If the live_add already exists, delete it and don't insert the live remove
        if ($addLive = $DB->get_record_sql('SELECT * FROM {languagelab_user_event} WHERE languagelab = ? AND userid = ? AND type = ? AND data LIKE ?', array($languagelab->id, $studentid, 'liveclass_add', '%' . $teacherlive . '%'))
        )
        {
            $DB->delete_records('languagelab_user_event', array('id' => $addLive->id));
            $insertEvent = false;
        }
    }
    else if ($eventType == 'listened_add')
    {
        
    }
    else if ($eventType == 'listened_remove')
    {
        //If the live_add already exists, delete it and don't insert the live remove
        //We use a SQL because get_records crashes with postgresql
        $sql = 'SELECT * FROM {languagelab_user_event} WHERE languagelab = ? AND userid = ? AND ' . $DB->sql_compare_text('type') . ' = ?';
        if ($addListened = $DB->get_records_sql($sql, array($languagelab->id, $studentid, 'listened_add')))
        //if ($addListened = $DB->get_record('languagelab_user_event', array('languagelab' => $languagelab->id, 'userid' => $studentid, 'type' => 'listened_add')))
        {
            $DB->delete_records('languagelab_user_event', array('id' => $addListened->id));
            $insertEvent = false;
        }
    }
    else if ($eventType == 'thumbs_up')
    {
        
    }

    $eventid = array();
    //Insert the element only if $insertEvent == true
    if ($insertEvent)
    {
        $obj->data = json_encode($obj->data);
        //If eventtype == liveclass_... loop for all users
        if ($eventType == 'liveclass_add' || $eventType == 'liveclass_remove')
        {

            //Get enrolled students
            $students = get_enrolled_users($context, 'mod/languagelab:studentview');
            foreach ($students as $student)
            {
                //Get students info
                if ($user_live = $DB->get_record('languagelab_user_live', array('languagelab' => $languagelab->id, 'userid' => $student->id)))
                {
                    //Only take connected students
                    if (((time() - $user_live->timemodified) < 20))
                    {
                        $obj->userid = $student->id;
                        $eventid[] = $DB->insert_record('languagelab_user_event', $obj);
                    }
                }
            }
        }
        else
        {
            $eventid[] = $DB->insert_record('languagelab_user_event', $obj);
        }
    }

    //Commit the transaction
    $DB->commit_delegated_transaction($transaction);

    //If it is live, wait for client answer
    if (!empty($eventid) && ($eventType == 'live_add' || $eventType == 'live_remove' || $eventType == 'liveclass_add' || $eventType == 'liveclass_remove'))
    {
        $max_nb_loops = 30;
        $nb_loops = 0;
        $connected = false;
        //While not found, loop...
        while (!$connected && $nb_loops <= $max_nb_loops)
        {
            if ($event = $DB->get_records_SQL('SELECT * FROM {languagelab_user_event} WHERE id IN (' . implode(',', $eventid) . ')'))
            {
                sleep(1);
            }
            else
            {
                $connected = true;
            }
            $nb_loops++;
        }

        if ($connected)
        {
            $result->success = true;
            if ($eventType == 'live_add')
            {
                $result->message = get_string('connected_student', 'languagelab');
                $result->data->title = get_string('connected_student_title', 'languagelab');
                $result->data->btnStop = get_string('connected_student_btnStop', 'languagelab');
            }
            if ($eventType == 'liveclass_add')
            {
                $result->message = get_string('connected_class', 'languagelab');
                $result->data->title = get_string('connected_class_title', 'languagelab');
                $result->data->btnStop = get_string('connected_class_btnStop', 'languagelab');
            }
        }
        else
        {
            $result->success = false;
            $result->message = get_string('error_cannot_connect_student', 'languagelab');
        }
    }
    else if (empty($eventid) && ($eventType == 'live_add' || $eventType == 'live_remove' || $eventType == 'liveclass_add' || $eventType == 'liveclass_remove'))
    {
        $result->message = get_string('connected_no_student_connected', 'languagelab');
        $result->data->title = get_string('connected_error', 'languagelab');
        $result->data->btnStop = get_string('connected_class_btnStop', 'languagelab');
    }



    echo json_encode($result);
}
else
{
    $teacher = $DB->get_record("user", array("id" => $USER->id));
    $teacherid = $USER->id;
    $teachername = fullname($teacher);
    //Moodle 2 makes it easier to print hte user picture, however, a little manipulation is necessary to grab the link
    //First get user picture
    $userpictureurl = $OUTPUT->user_picture($teacher, array('courseid' => $course->id, 'link' => false));
    //create an array from the image tag
    $newuserpictureurl = explode(' ', $userpictureurl);
    //Get the link info from array row 1 and remove src="
    $teacherpicture = str_replace('src="', '', $newuserpictureurl[1]);
    //remove last double quotation marks;
    $teacherpicture = str_replace('"', '', $teacherpicture);
    //***************************End Teacher Information**************************************
    //****************Get students *************************************
    $students = get_enrolled_users($context, 'mod/languagelab:studentview');
    //**************************************************************************
    //**********JSON OUTPUT******************

    $json = array();

    foreach ($students as $student)
    {
        $elem = new stdClass();
        $elem->id = $student->id;
        $elem->live = '';


        if ($user_live = $DB->get_record('languagelab_user_live', array('languagelab' => $languagelab->id, 'userid' => $student->id)))
        {
            $elem->live = $user_live->live;
            $elem->online = ((time() - $user_live->timemodified) < 20);
        }
        else
        {
            $elem->online = false;
        }
        $json[] = $elem;
    }

    $obj = new stdClass();
    $obj->checksum = md5(json_encode($json));
    $obj->json = $json;
    $obj->hands = array();
    //If there is student who raised the hand
    //We use a SQL because get_records crashes with postgresql
    $sql = 'SELECT * FROM {languagelab_user_event} WHERE languagelab = ? AND userid = ? AND ' . $DB->sql_compare_text('type') . ' = ?';
    if ($handsraised = $DB->get_records_sql($sql, array($languagelab->id, $USER->id, 'raise_hand')))
    //if ($handsraised = $DB->get_records('languagelab_user_event', array('languagelab' => $languagelab->id, 'userid' => $USER->id, 'type' => 'raise_hand')))
    {
        foreach ($handsraised as $handRaised)
        {
            $handRaised->data = json_decode($handRaised->data);
            $obj->hands[] = $handRaised;
            $DB->delete_records('languagelab_user_event', array('id' => $handRaised->id));
        }
    }

    echo json_encode($obj);
}
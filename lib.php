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
defined('MOODLE_INTERNAL') || die();

/**
 * List of features supported in Tab display
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function languagelab_supports($feature)
{
    switch ($feature)
    {
        case FEATURE_IDNUMBER: return false;
        case FEATURE_GROUPS: return true;
        case FEATURE_GROUPINGS: return true;
        case FEATURE_GROUPMEMBERSONLY: return true;
        case FEATURE_MOD_INTRO: return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE: return true;
        case FEATURE_GRADE_OUTCOMES: return false;
        case FEATURE_MOD_ARCHETYPE: return MOD_ARCHETYPE_ASSIGNMENT;
        case FEATURE_BACKUP_MOODLE2: return true;

        default: return null;
    }
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will create a new instance and return the id number 
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted languagelab record
 * @global moodle_database $DB
 * @global core_renderer $OUTPUT
 * @global moodle_page $PAGE.

 * */
function languagelab_add_instance($languagelab, $mform = null)
{
    global $DB, $CFG;

    require_once("$CFG->libdir/resourcelib.php");

    $draftitemid = $languagelab->content['itemid'];
    $cmid = $languagelab->coursemodule;

    //Replace get_context_instance by the class for moodle 2.6+
    if (class_exists('context_module'))
    {
        $context = context_module::instance($cmid);
    }
    else
    {
        $context = get_context_instance(CONTEXT_MODULE, $cmid);
    }
    //Remove once re-implemented
    $languagelab->recording_timelimit = 0;
    $languagelab->contentformat = $languagelab->content['format'];
    $languagelab->description = $languagelab->content['text'];
    $languagelab->timemodified = time();
    //Check to see that a value is set for use_mp3
    if (isset($languagelab->use_mp3))
    {
        $languagelab->use_mp3 = 1;
    }
    else
    {
        $languagelab->use_mp3 = 0;
    }
    if (isset($languagelab->video))
    {
        $languagelab->video = 1;
    }
    else
    {
        $languagelab->video = 0;
    }
    if (isset($languagelab->use_grade_book))
    {
        $languagelab->use_grade_book = 1;
    }
    else
    {
        $languagelab->use_grade_book = 0;
    }
    //Uploaded file
    if ($mform)
    {
        $filename = $mform->get_new_filename('master_track');
        if ($filename !== false)
        {
            $data = base64_encode($mform->get_file_content('master_track'));

            $filename = $CFG->languagelab_folder . '/' . $CFG->languagelab_prefix . 'mastertrack_' . rand(10000000, 99999999);
            languagelab_upload_mp3_file($data, $filename . '.mp3');

            $languagelab->master_track = 'mp3:' . $filename;
            $languagelab->master_track_recording = $languagelab->master_track;
        }
        else
        {
            if (languagelab_convert_recording($languagelab->master_track_recording, 'mp3') == 1)
            {
                $languagelab->master_track_recording = 'mp3:' . $languagelab->master_track_recording;
            }
            $languagelab->master_track = $languagelab->master_track_recording;
        }
    }


    $languagelab->id = $DB->insert_record("languagelab", $languagelab);

    //only use grade book when checked
    if (isset($languagelab->use_grade_book))
    {
        $languagelab->cmidnumber = $cmid;
        languagelab_grade_item_update($languagelab);
    }
    // we need to use context now, so we need to make sure all needed info is already in db
    $DB->set_field('course_modules', 'instance', $languagelab->id, array('id' => $cmid));

    //Replace get_context_instance by the class for moodle 2.6+
    if (class_exists('context_module'))
    {
        $context = context_module::instance($cmid);
    }
    else
    {
        $context = get_context_instance(CONTEXT_MODULE, $cmid);
    }
    $editoroptions = languagelab_get_editor_options($context);
    if ($draftitemid)
    {
        $languagelab->description = file_save_draft_area_files($draftitemid, $context->id, 'mod_languagelab', 'content', $languagelab->id, $editoroptions, $languagelab->description);
        $DB->update_record('languagelab', $languagelab);
    }
    
    //Update the student calendar
    languagelab_update_calendar($languagelab);

    //Create the folder on the RTMP server
    languagelab_adapter_call('create_folder', 's=' . $CFG->languagelab_folder . '/' . $cmid);

    return $languagelab->id;
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 * */
function languagelab_update_instance($languagelab, $mform = null)
{
    global $CFG, $DB;

    $cmid = $languagelab->coursemodule;
    //Replace get_context_instance by the class for moodle 2.6+
    if (class_exists('context_module'))
    {
        $context = context_module::instance($cmid);
    }
    else
    {
        $context = get_context_instance(CONTEXT_MODULE, $cmid);
    }
    $draftitemid = $languagelab->content['itemid'];
    $languagelab->contentformat = $languagelab->content['format'];
    $languagelab->description = $languagelab->content['text'];
    $languagelab->timemodified = time();
    $languagelab->id = $languagelab->instance;
    //Check to see that a value is set for use_mp3
    if (isset($languagelab->use_mp3))
    {
        $languagelab->use_mp3 = 1;
    }
    else
    {
        $languagelab->use_mp3 = 0;
    }
    if (isset($languagelab->video))
    {
        $languagelab->video = 1;
    }
    else
    {
        $languagelab->video = 0;
    }
    if (isset($languagelab->use_grade_book))
    {
        $languagelab->use_grade_book = 1;
    }
    else
    {
        $languagelab->use_grade_book = 0;
    }
    //Uploaded file
    if ($languagelab->submitted_recordings == 0)
    {
        if ($mform)
        {
            $filename = $mform->get_new_filename('master_track');
            if ($filename !== false)
            {

                $data = base64_encode($mform->get_file_content('master_track'));

                $filename = $CFG->languagelab_folder . '/' . $CFG->languagelab_prefix . 'mastertrack_' . rand(10000000, 99999999);
                languagelab_upload_mp3_file($data, $filename . '.mp3');

                $languagelab->master_track = 'mp3:' . $filename;
                $languagelab->master_track_recording = $languagelab->master_track;
            }
            else
            {

                if ($languagelab->use_mp3 == true)
                {
                    $languagelab->master_track = $languagelab->master_track_used;
                }
                else
                {
                    if (strpos($languagelab->master_track_recording, 'mp3:') === false)
                    {
                        if (languagelab_convert_recording($languagelab->master_track_recording, 'mp3') == 1)
                        {
                            $languagelab->master_track_recording = 'mp3:' . $languagelab->master_track_recording;
                        }
                    }
                    $languagelab->master_track = $languagelab->master_track_recording;
                }
            }
        }
    }
    # May have to add extra stuff in here #
    $temp = $DB->update_record("languagelab", $languagelab);

    //only use grade book when checked
    if (isset($languagelab->use_grade_book) && $languagelab->use_grade_book == true)
    {
        // update grade item definition
        $languagelab->cmidnumber = $cmid;
        languagelab_grade_item_update($languagelab);

        // update grades - TODO: do it only when grading style changes
        languagelab_update_grades($languagelab, 0, false);
    }

    //Replace get_context_instance by the class for moodle 2.6+
    if (class_exists('context_module'))
    {
        $context = context_module::instance($cmid);
    }
    else
    {
        $context = get_context_instance(CONTEXT_MODULE, $cmid);
    }
    $editoroptions = languagelab_get_editor_options($context);
    if ($draftitemid)
    {
        $languagelab->description = file_save_draft_area_files($draftitemid, $context->id, 'mod_languagelab', 'content', $languagelab->id, $editoroptions, $languagelab->description);
        $DB->update_record('languagelab', $languagelab);
    }
    
    //Update the student calendar
    languagelab_update_calendar($languagelab);

    //Create the folder on the RTMP server
    languagelab_adapter_call('create_folder', 's=' . $CFG->languagelab_folder . '/' . $cmid);

    return true;
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 * */
function languagelab_delete_instance($id)
{
    global $DB, $CFG;

    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
        //Get all language lab recordings
        $master_track = $DB->get_record('languagelab', array('id' => $id));
        $master_track_recording = $master_track->master_track_recording;
        //Get student submissions
        if ($submissions = $DB->get_records('languagelab_submissions', array('languagelab' => $id)))
        {
            $submissions = json_encode($submissions);
        }
        else
        {
            $submissions = '';
        }

        $result = languagelab_adapter_call('delete', "s=$submissions&m=$master_track_recording");
    }
    //*************End RAP********************************************
    if (!$languagelab = $DB->get_record("languagelab", array("id" => $id)))
    {
        return false;
    }

    # Delete any dependent records here #
    $DB->delete_records("languagelab_student_eval", array("languagelab" => $languagelab->id));
    $DB->delete_records("languagelab_submissions", array("languagelab" => $languagelab->id));

    if (!$DB->delete_records("languagelab", array("id" => $languagelab->id)))
    {
        $result = false;
    }
    languagelab_grade_item_delete($languagelab);

    $result = true;

    return $result;
}

/**
 * Return a small object with summary information about what a 
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 * */
function languagelab_user_outline($course, $user, $mod, $languagelab)
{
    $return = new stdClass;
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 * */
function languagelab_user_complete($course, $user, $mod, $languagelab)
{
    return true;

    $submissions = $DB->get_records('languagelab_submissions', array('languagelab' => $languagelab->id, 'userid' => $user->id));
}

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in languagelab activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 * */
function languagelab_print_recent_activity($course, $viewfullnames, $timestart)
{
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such 
 * as sending out mail, toggling flags etc ... 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 * */
function languagelab_cron()
{
    return true;
}

/**
 * Must return an array of grades for a given instance of this module, 
 * indexed by user.  It also returns a maximum allowed grade.
 * 
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 * */
function languagelab_get_user_grades($languagelab, $userid = 0)
{
    global $CFG, $DB;

    $user = $userid ? "AND u.id = $userid" : "";
    $fuser = $userid ? "AND uu.id = $userid" : "";

    $sql = "SELECT u.id, u.id AS userid, AVG(g.grade) AS rawgrade
                      FROM {user} u, {languagelab_student_eval} g
                     WHERE u.id = g.userid AND g.languagelab = $languagelab->id
                           $user
                  GROUP BY u.id";

    return $DB->get_records_sql($sql);
}

/**
 * Update grades in central gradebook
 * @global stdClass $CFG
 * @global moodle_database $DB
 * @param stdClass $languagelab
 * @param int $userid
 * @param boolean $nullifnone 
 */
function languagelab_update_grades($languagelab = null, $userid = 0, $nullifnone = true)
{
    global $CFG, $DB;
    if (!function_exists('grade_update'))
    { //workaround for buggy PHP versions
        require_once($CFG->libdir . '/gradelib.php');
    }

    if ($languagelab != null)
    {
        if ($grades = languagelab_get_user_grades($languagelab, $userid))
        {
            languagelab_grade_item_update($languagelab, $grades);
        }
        else if ($userid and $nullifnone)
        {
            $grade = new object();
            $grade->userid = $userid;
            $grade->rawgrade = NULL;
            languagelab_grade_item_update($languagelab, $grade);
        }
        else
        {
            languagelab_grade_item_update($languagelab);
        }
    }
    else
    {
        $sql = "SELECT l.*, cm.idnumber as cmidnumber, l.course as courseid
                  FROM {languagelab} l, {course_modules} cm, {modules} m
                 WHERE m.name='languagelab' AND m.id=cm.module AND cm.instance=l.id";
        if ($rs = $DB->get_recordset_sql($sql))
        {
            while ($languagelab = rs_fetch_next_record($rs))
            {
                if ($languagelab->grade != 0)
                {
                    languagelab_update_grades($languagelab, 0, false);
                }
                else
                {
                    languagelab_grade_item_update($languagelab);
                }
            }
            rs_close($rs);
        }
    }
}

/**
 * Create grade item for given languagelab
 *
 * @param stdClass $languagelab object with extra cmidnumber
 * @param mixed $grades optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok, error code otherwise
 */
function languagelab_grade_item_update($languagelab, $grades = NULL)
{
    global $CFG;

    if (!function_exists('grade_update'))
    { //workaround for buggy PHP versions
        require_once($CFG->libdir . '/gradelib.php');
    }

    if (array_key_exists('cmidnumber', $languagelab))
    { //it may not be always present
        $params = array('itemname' => $languagelab->name, 'idnumber' => $languagelab->cmidnumber);
    }
    else
    {
        $params = array('itemname' => $languagelab->name);
    }

    if ($languagelab->grade > 0)
    {
        $params['gradetype'] = GRADE_TYPE_VALUE;
        $params['grademax'] = $languagelab->grade;
        $params['grademin'] = 0;
    }
    else
    {
        $params['gradetype'] = GRADE_TYPE_NONE;
    }

    if ($grades === 'reset')
    {
        $params['reset'] = true;
        $grades = NULL;
    }
    else if (!empty($grades))
    {
        // Need to calculate raw grade (Note: $grades has many forms)
        if (is_object($grades))
        {
            $grades = array($grades->userid => $grades);
        }
        else if (array_key_exists('userid', $grades))
        {
            $grades = array($grades['userid'] => $grades);
        }
        foreach ($grades as $key => $grade)
        {
            if (!is_array($grade))
            {
                $grades[$key] = $grade = (array) $grade;
            }
            $grades[$key]['rawgrade'] = ($grade['rawgrade'] * $languagelab->grade / 100);
        }
    }

    return grade_update('mod/languagelab', $languagelab->course, 'mod', 'languagelab', $languagelab->id, 0, $grades, $params);
}

/**
 * Delete grade item for given languagelab
 *
 * @param object $languagelab object
 * @return object languagelab
 */
function languagelab_grade_item_delete($languagelab)
{
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    return grade_update('mod/languagelab', $languagelab->course, 'mod', 'languagelab', $languagelab->id, 0, NULL, array('deleted' => 1));
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of languagelab. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed boolean/array of students
 * */
function languagelab_get_participants($languagelabid)
{
    return false;
}

/**
 * This function returns if a scale is being used by one languagelab
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 * */
function languagelab_scale_used($languagelabid, $scaleid)
{
    global $DB;

    $return = false;

    //$rec = $DB->get_record("newmodule", array("id" => "$newmoduleid", "scale" => "-$scaleid"));
    //
    //if (!empty($rec) && !empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}

/**
 * Checks if scale is being used by any instance of newmodule.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any newmodule
 */
function languagelab_scale_used_anywhere($scaleid)
{
    global $DB;

    if ($scaleid and $DB->record_exists('languagelab', array('grade' => -$scaleid)))
    {
        return true;
    }
    else
    {
        return false;
    }
}

//Needed for ajax to get languagelabid 
function get_languagelab_id($languagelab)
{
    $languagelabid = $languagelab;
    return $languagelabid;
}

/**
 * Lists all browsable file areas
 *
 * @package  mod_languagelab
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @return array
 */
function languagelab_get_file_areas($course, $cm, $context)
{
    $areas = array();
    $areas['content'] = get_string('content', 'languagelab');
    return $areas;
}

/**
 * File browsing support for languagelab module content area.
 *
 * @package  mod_languagelab
 * @category files
 * @param stdClass $browser file browser instance
 * @param stdClass $areas file areas
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param int $itemid item ID
 * @param string $filepath file path
 * @param string $filename file name
 * @return file_info instance or null if not found
 */
function languagelab_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename)
{
    global $CFG;

    if (!has_capability('moodle/course:managefiles', $context))
    {
        // students can not peak here!
        return null;
    }

    $fs = get_file_storage();

    if ($filearea === 'content')
    {
        $filepath = is_null($filepath) ? '/' : $filepath;
        $filename = is_null($filename) ? '.' : $filename;

        $urlbase = $CFG->wwwroot . '/pluginfile.php';
        if (!$storedfile = $fs->get_file($context->id, 'mod_languagelab', 'content', $itemid, $filepath, $filename))
        {
            if ($filepath === '/' and $filename === '.')
            {
                $storedfile = new virtual_root_file($context->id, 'mod_languagelab', 'content', $itemid);
            }
            else
            {
                // not found
                return null;
            }
        }
        require_once("$CFG->dirroot/mod/languagelab/locallib.php");
        return new languagelab_content_file_info($browser, $context, $storedfile, $urlbase, $areas[$filearea], true, true, true, false);
    }

    // note: page_intro handled in file_browser automatically

    return null;
}

function languagelab_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload)
{
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");

    if ($context->contextlevel != CONTEXT_MODULE)
    {
        return false;
    }

    require_course_login($course, true, $cm);


    if ($filearea == 'content')
    {

        //Get languagelab-> id from file_rewrite_pluginfile_urls ***IMPORTANT** Otherwisefiles won't display!!!!
        $languagelabid = (int) array_shift($args);

        $fs = get_file_storage();
        $relativepath = implode('/', $args);
        $fullpath = "/$context->id/mod_languagelab/$filearea/$languagelabid/$relativepath";
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory())
        {
            $languagelab = $DB->get_record('languagelab', array('id' => $cm->instance), 'id, legacyfiles', MUST_EXIST);
            if ($languagelab->legacyfiles != RESOURCELIB_LEGACYFILES_ACTIVE)
            {
                return false;
            }
            if (!$file = resourcelib_try_file_migration('/' . $relativepath, $cm->id, $cm->course, 'mod_languagelab', 'content', 0))
            {
                return false;
            }
            //file migrate - update flag
            $languagelab->legacyfileslast = time();
            $DB->update_record('languagelab', $languagelab);
        }
    }
    else
    {
        $fs = get_file_storage();
        $relativepath = implode('/', $args);
        $fullpath = "/$context->id/mod_languagelab/mastertrack/$relativepath";
        echo "<br>$fullpath";
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory())
        {
            return false;
        }
    }
    // finally send the file
    send_stored_file($file, 86400, 0, $forcedownload);
}

//////////////////////////////////////////////////////////////////////////////////////
/// Any other languagelab functions go here.  Each of them must have a name that 
/// starts with languagelab_

/**
 * Convert the file from FLV to MP3
 * @param string $filePath the file path on the server
 * @param string $type The type of recording mp3 or mp4
 */
function languagelab_convert_recording($filePath, $type)
{

    require_once('locallib.php');
    return convert_recording($filePath, $type);
}

/**
 * Send a mp3 file to the RED5 server
 * @param type $filedata The mp3 data
 */
function languagelab_upload_mp3_file($filedata, $pathOnServer)
{
    require_once('locallib.php');
    return upload_mp3_file($filedata, $pathOnServer);
}

/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the languagelab
 *
 * @param $mform form passed by reference
 */
function languagelab_reset_course_form_definition(&$mform)
{
    $mform->addElement('header', 'languagelabheader', get_string('modulenameplural', 'languagelab'));
    $mform->addElement('advcheckbox', 'reset_languagelab', get_string('deletealldata', 'languagelab'));
}

/**
 * Course reset form defaults.
 * @param object $course
 * @return array
 */
function languagelab_reset_course_form_defaults($course)
{
    return array('reset_languagelab' => 1);
}

/**
 * Removes all grades from gradebook
 *
 * @global stdClass
 * @global object
 * @param int $courseid
 * @param string optional type
 */
function languagelab_reset_gradebook($courseid, $type = '')
{
    global $CFG, $DB;

    $sql = "SELECT ll.*, cm.idnumber as cmidnumber, ll.course as courseid
              FROM {languagelab} ll, {course_modules} cm, {modules} m
             WHERE m.name='languagelab' AND m.id=cm.module AND cm.instance=ll.id AND ll.course=:course";
    $params = array("course" => $courseid);
    if ($languagelabs = $DB->get_records_sql($sql, $params))
    {
        foreach ($languagelabs as $languagelab)
        {
            languagelab_grade_item_update($languagelab, 'reset');
        }
    }
}

/**
 * Actual implementation of the reset course functionality, delete all the
 * languagelab attempts for course $data->courseid.
 *
 * @global stdClass
 * @global object
 * @param object $data the data submitted from the reset course.
 * @return array status array
 */
function languagelab_reset_userdata($data)
{
    global $CFG, $DB;

    $componentstr = get_string('modulenameplural', 'languagelab');
    $status = array();

    if (!empty($data->reset_languagelab))
    {
        $languagelabssql = "SELECT ll.id
                         FROM {languagelab} ll
                        WHERE ll.course=:course";

        $params = array("course" => $data->courseid);
        $DB->delete_records_select('languagelab_submissions', "languagelab IN ($languagelabssql)", $params);
        $DB->delete_records_select('languagelab_student_eval', "languagelab IN ($languagelabssql)", $params);

        // remove all grades from gradebook
        if (empty($data->reset_gradebook_grades))
        {
            languagelab_reset_gradebook($data->courseid);
        }

        $status[] = array('component' => $componentstr, 'item' => get_string('deletealldata', 'languagelab'), 'error' => false);
    }

    /// updating dates - shift may be negative too
    if ($data->timeshift)
    {
        shift_course_mod_dates('languagelab', array('available', 'deadline'), $data->timeshift, $data->courseid);
        $status[] = array('component' => $componentstr, 'item' => get_string('datechanged'), 'error' => false);
    }

    return $status;
}

/**
 * Execute a CURL Action on the adapter
 * @global stdClass $CFG
 * @param string $action The name of the action to perform
 * @param array/string $params The string of params to send to the adapter, like q=myfile.mp3&s=myfile2.mp3
 * @return string return the result of the call
 */
function languagelab_adapter_call($action, $params)
{
    global $CFG;

    //Let's delete all files on the Red5 Server
    $Red5Server = $CFG->languagelab_adapter_server;
    $prefix = $CFG->languagelab_prefix;
    $salt = $CFG->languagelab_salt;
    //RAP security
    if ($CFG->languagelab_adapter_access == true)
    {
        $security = 'https://';
    }
    else
    {
        $security = 'http://';
    }
    $url = "$security$Red5Server/$CFG->languagelab_adapter_file.php";

    //Encrypt information
    $q = md5($Red5Server . $prefix . $salt);
    //Action convert
    $o = md5($action . $salt);

    if (is_array($params))
    {
        $vars = $params;
        $vars['q'] = $q;
        $vars['o'] = $o;
    }
    else
    {
        $vars = "q=$q&o=$o&$params";
    }

    //Send request to red5 server using curl
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * 
 * @global moodle_database $DB
 * @param type $ll_id
 * @param type $getprevious
 */
function languagelab_get_previous_next_lab_url($ll_id, $getprevious = true)
{
    global $DB, $COURSE;

    $url = '';
    $prev = null;
    $next = null;
    $founded = false;
    $course_info = get_fast_modinfo($COURSE->id);
    $languagelabs = $course_info->instances['languagelab'];

    foreach ($languagelabs as $languagelab)
    {
        if ($languagelab->id == $ll_id)
        {
            $founded = true;
            continue;
        }
        if ($founded)
        {
            $next = $languagelab;
            break;
        }
        $prev = $languagelab;
    }

    if ($founded && $getprevious && $prev != null)
    {
        $url = $prev->get_url();
    }
    else if ($founded && !$getprevious && $next != null)
    {
        $url = $next->get_url();
    }
    return $url;
}

/**
 * Update the calendar entries for this languagelab.
 *
 * @param stdClass $languagelab The language lab instance
 * @return bool
 */
function languagelab_update_calendar($languagelab)
{
    global $DB, $CFG;
    require_once($CFG->dirroot . '/calendar/lib.php');

    if (class_exists('calendar_event') && $languagelab->timedue)
    {
        $event = new stdClass();

        $params = array('modulename' => 'languagelab', 'instance' => $languagelab->id);
        $event->id = $DB->get_field('event', 'id', $params);
        $event->name = $languagelab->name;
        $event->timestart = $languagelab->timedue;

        // Convert the links to pluginfile. It is a bit hacky but at this stage the files
        // might not have been saved in the module area yet.
        $intro = $languagelab->description;
        if ($draftid = file_get_submitted_draft_itemid('introeditor'))
        {
            $intro = file_rewrite_urls_to_pluginfile($intro, $draftid);
        }

        // We need to remove the links to files as the calendar is not ready
        // to support module events with file areas.
        // Function strip_pluginfile_content appears in moodle 2.6.
        if(function_exists('strip_pluginfile_content'))
        {
            $intro = strip_pluginfile_content($intro);
        }
        else
        {
            //Do the strip_pluginfile_content manually
            $baseurl = '@@PLUGINFILE@@';
            // Looking for something like < .* "@@pluginfile@@.*" .* >
            $pattern = '$<[^<>]+["\']' . $baseurl . '[^"\']*["\'][^<>]*>$';
            $stripped = preg_replace($pattern, '', $intro);
            // Use purify html to rebalence potentially mismatched tags and generally cleanup.
            $intro = purify_html($stripped);
        }
        
        $event->description = array(
            'text' => $intro,
            'format' => $languagelab->contentformat
        );

        if ($event->id)
        {
            $calendarevent = calendar_event::load($event->id);
            $calendarevent->update($event);
        }
        else
        {
            unset($event->id);
            $event->courseid = $languagelab->course;
            $event->groupid = 0;
            $event->userid = 0;
            $event->modulename = 'languagelab';
            $event->instance = $languagelab->id;
            $event->eventtype = 'due';
            $event->timeduration = 0;
            calendar_event::create($event);
        }
    }
    else
    {
        $DB->delete_records('event', array('modulename' => 'languagelab', 'instance' => $languagelab->id));
    }
}

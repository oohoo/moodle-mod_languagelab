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
require_once(dirname(__FILE__) . '/../../config.php');
require_once("$CFG->libdir/filelib.php");
require_once("$CFG->libdir/resourcelib.php");

global $CFG;

/**
 * 
 * @param type $domain
 * @return boolean 
 */
function isDomainAvailible($domain)
{
    //check, if a valid url is provided
    if (!filter_var($domain, FILTER_VALIDATE_URL))
    {
        return false;
    }

    //initialize curl
    $curlInit = curl_init($domain);
    curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curlInit, CURLOPT_HEADER, true);
    curl_setopt($curlInit, CURLOPT_NOBODY, true);
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

    //get answer
    $response = curl_exec($curlInit);

    curl_close($curlInit);

    if ($response)
        return true;

    return false;
}

function delete_individual_recording($submission_id)
{
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        //Action delete
        $o = md5('delete' . $salt);

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


        $vars = "q=$q&o=$o&s=$submissions&m=$master_track_recording";

        //Send request to red5 server using curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);

        $result = curl_exec($ch);
    }
}

function delete_single_recording($submission_id)
{
    global $CFG, $DB;
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        //Action delete
        $o = md5('delete_single' . $salt);

        //Get student submissions
        if ($submission = $DB->get_record('languagelab_submissions', array('id' => $submission_id)))
        {
            if (strpos($submission->path, 'mp3:') !== false)
            {
                $submission->path = substr($submission->path, 4);
            }
            $submission = json_encode(array($submission));
        }
        else
        {
            $submission = '';
        }


        $vars = "q=$q&o=$o&s=$submission";

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
    //*************End RAP********************************************
}

/**
 * Return the URL for the mp3 download
 * @param boolean $videoMode If video Mode = true Download MP4
 */
function get_download_url($videoMode = false)
{
    global $CFG, $DB;
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        //Action download
        $o = md5('download_mp3' . $salt);
        if ($videoMode)
        {
            $o = md5('download_mp4' . $salt);
        }

        return $url . '?q=' . $q . '&o=' . $o;
    }
}

//Return the URL for the zip download
function get_download_zip_url()
{
    global $CFG, $DB;
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        //Action delete
        $o = md5('download_zip' . $salt);

        return $url . '?q=' . $q . '&o=' . $o;
    }
}

/**
 * Convert the file from FLV to MP4
 * @global type $CFG
 * @global type $DB
 * @param type $filePath the file path on the server
 */
function convert_recording($filePath, $type = 'mp3')
{
    global $CFG, $DB;
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        $o = md5('convert_' . $type . '_single' . $salt);


        $vars = "q=$q&o=$o&s=$filePath";

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
    //*************End RAP********************************************
}

/**
 * Convert the file from FLV to MP3
 * @global type $CFG
 * @global type $DB
 * @param type $filePath the file path on the server
 */
function move_mp3_recording($oldpath, $newpath)
{
    global $CFG, $DB;
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        //Action move
        $o = md5('move_mp3_single' . $salt);


        $vars = "q=$q&o=$o&s=$oldpath&n=$newpath";

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
    //*************End RAP********************************************
}

/**
 * Convert the file from FLV to MP3
 * @global type $CFG
 * @global type $DB
 * @param type $activityid the activity ID
 */
function migrate_all_flv_to_mp3_recording($activityid)
{
    global $CFG, $DB, $USER;

    $cm = get_coursemodule_from_id('languagelab', $activityid, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $languagelab = $DB->get_record('languagelab', array('id' => $cm->instance), '*', MUST_EXIST);

    require_login($course, true, $cm);

    if (!is_siteadmin($USER))
    {
        error('Only administrators can execute this function');
    }
    else
    {
        //Is the Red5 Adapter Plugin set
        if (isset($CFG->languagelab_adapter_file))
        {
            //Let's delete all files on the Red5 Server
            $Red5Server = $CFG->languagelab_adapter_server;
            $prefix = $CFG->languagelab_prefix;
            $salt = $CFG->languagelab_salt;

            $llabFolder = $CFG->languagelab_folder;

            //Check the mastertrack
            //If the mastertrack is a mp3, if not, convert it
            //If the mastertrack is in the good root folder. moodler folder, else move it
            //If the prefix is good, else rename it
            //If the file is not on the server, grab it and download it in the server

            $updateMastertrackFail = false;
            if ($languagelab->master_track != '')
            {
                if ($languagelab->use_mp3 == 0)
                {
                    $originalPath = $languagelab->master_track;

                    $bFileToConvert = false;
                    $bFileToMove = false;

                    //The file is not a mp3
                    if (strpos($languagelab->master_track, 'mp3:') === false)
                    {
                        $bFileToConvert = true;
                        echo '- Mastertrack ' . $languagelab->master_track . ' need to be convert in mp3... ';
                        //Convert the file
                        if (convert_recording($languagelab->master_track, 'mp3') == 1)
                        {
                            $languagelab->master_track = 'mp3:' . $languagelab->master_track;
                            echo 'CONVERSION SUCCESSFUL<br />';
                        }
                        else
                        {
                            echo '<b>CONVERSION FAILED</b><br />';
                            $updateMastertrackFail = true;
                        }
                    }

                    if (!$updateMastertrackFail)
                    {
                        $path = explode(':', $languagelab->master_track);
                        $ext = '';
                        if (count($path) > 1)
                        {
                            $ext = $path[0] . ':';
                            $path = $path[1];
                        }
                        else
                        {
                            $path = $path[0];
                        }

                        echo '- Mastertrack ' . $languagelab->master_track . ' ';

                        //Check the path of the folder
                        if (strpos($path, $llabFolder) !== 0)
                        {
                            $bFileToMove = true;
                            echo ' | Main folder not good ';
                        }

                        //Check the folder with the ID of the activity
                        $folders = explode('/', $path);

                        //Check the prefix of the file
                        if (strpos($folders[count($folders) - 1], $prefix . 'mastertrack_') === false)
                        {
                            $bFileToMove = true;

                            echo ' | Prefix file not good ';
                        }

                        //If file must be moved
                        if ($bFileToMove)
                        {
                            $newpath = $llabFolder . '/' . $prefix . 'mastertrack_' . rand(10000000, 99999999);
                            echo '...';
                            //Move the file
                            if (move_mp3_recording($path, $newpath) == 1)
                            {
                                echo 'FILE MOVING SUCCESSFUL<br />';
                            }
                            else
                            {
                                echo '<b>FILE MOVING FAILED</b><br />';
                                $updateMastertrackFail = true;
                            }
                        }
                        else
                        {
                            $newpath = $path;
                        }

                        if (!$updateMastertrackFail && ($bFileToConvert || $bFileToMove))
                        {
                            $languagelab->use_mp3 = 0;
                            $languagelab->master_track = $ext . $newpath;
                            $languagelab->master_track_recording = $languagelab->master_track;


                            $DB->update_record('languagelab', $languagelab);
                            echo '- Languagelab ' . $activityid . ' updated!<br />';
                        }
                        else if (!$updateMastertrackFail)
                        {
                            echo '... MASTERTRACK OK<br />';
                        }
                    }
                }
                else
                {
                    //todo use mp3
                    //Basicaly: Get the mastertrack file content
                    //call upload_mp3_file
                    //Save the languagelab
                }
            }


            //Get all submissions
            $submissions = $DB->get_records('languagelab_submissions', array('languagelab' => $languagelab->id));

            //For each submission:
            //if Video mode disabled: Check if it is a mp3 file: If not, convert it in mp3
            //Check if the root folder is the same as the moodle configuration if not migration needed
            //Check if the "id folder" is the same as the languagelab id: If not, migration needed
            //Check if the Prefix file name is the same as the prefixe file name in the moodle configuration
            foreach ($submissions as $submission)
            {
                $bFileToConvert = false;
                $bFileToMove = false;
                $gotoNextSubmission = false;

                $originalPath = $submission->path;

                //The file is not a mp3
                if ($languagelab->video == 0 && strpos($submission->path, 'mp3:') === false)
                {
                    $bFileToConvert = true;
                    echo '- File ' . $submission->path . ' need to be convert in mp3... ';
                    //Convert the file
                    if (convert_recording($submission->path, 'mp3') == 1)
                    {
                        $submission->path = 'mp3:' . $submission->path;
                        echo 'CONVERSION SUCCESSFUL<br />';
                    }
                    else
                    {
                        echo '<b>CONVERSION FAILED</b><br />';
                        $gotoNextSubmission = true;
                    }
                }

                //If the conversion was successful
                if (!$gotoNextSubmission)
                {
                    $path = explode(':', $submission->path);
                    $ext = '';
                    if (count($path) > 1)
                    {
                        $ext = $path[0] . ':';
                        $path = $path[1];
                    }
                    else
                    {
                        $path = $path[0];
                    }

                    echo '- File ' . $submission->path . ' ';

                    //Check the path of the folder
                    if (strpos($path, $llabFolder) !== 0)
                    {
                        $bFileToMove = true;
                        echo ' | Main folder not good ';
                    }

                    //Check the folder with the ID of the activity
                    $folders = explode('/', $path);
                    //If the folder count is less than 3 (because "$llabFolder/IDACTIVITY/MP3FILE")
                    if (count($folders) < 3)
                    {
                        $bFileToMove = true;
                        echo ' | missing folder with activity ID ';
                    }
                    else if ($folders[1] != $activityid)
                    {
                        $bFileToMove = true;
                        echo ' | Second folder not good ';
                    }

                    //Check the prefix of the file
                    if (strpos($folders[count($folders) - 1], $prefix) === false)
                    {
                        $bFileToMove = true;

                        echo ' | Prefix file not good ';
                    }

                    //If file must be moved
                    if ($bFileToMove)
                    {
                        $newpath = $llabFolder . '/' . $activityid . '/' . $prefix . rand(10000000, 99999999);
                        echo '...';
                        //Move the file
                        if (move_mp3_recording($path, $newpath) == 1)
                        {
                            echo 'FILE MOVING SUCCESSFUL<br />';
                        }
                        else
                        {
                            echo '<b>FILE MOVING FAILED</b><br />';
                            $gotoNextSubmission = true;
                        }
                    }
                    else
                    {
                        $newpath = $path;
                    }

                    if (!$gotoNextSubmission && ($bFileToConvert || $bFileToMove))
                    {
                        $submission->path = $ext . $newpath;
                        $childrenSubmissions = $DB->get_records('languagelab_submissions', array('parentnode' => $originalPath));
                        foreach ($childrenSubmissions as $child)
                        {
                            $child->parentnode = $submission->path;
                            $DB->update_record('languagelab_submissions', $child);
                        }
                        $DB->update_record('languagelab_submissions', $submission);
                        echo '- Submission ' . $submission->id . ' updated! ' . count($childrenSubmissions) . ' children updated too!<br />';
                    }
                    else if (!$gotoNextSubmission)
                    {
                        echo '... FILE OK<br />';
                    }
                }
            }
        }
    }
}

/**
 * Upload a mp3 file on the red5 server
 * @global type $CFG
 * @global type $DB
 * @param type $filedata the mp3 file data
 */
function upload_mp3_file($filedata, $pathOnServer)
{
    global $CFG, $DB;
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
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
        //Action delete
        $o = md5('upload_mp3' . $salt);


        $vars = array("q" => $q,
            "o" => $o,
            "p" => $pathOnServer,
            "d" => $filedata);

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
    //*************End RAP********************************************
}

/**
 * Return a date formatted in function of the length (years, months, days, etc.)
 * @param int $time the Time in seconds
 * @return string  The formatted time
 */
function formatTimeSince($time)
{
    $tNow = time();
    $nSecs = $tNow - $time;
    $sTimeSince = "";
    switch (true)
    {
        case ($nSecs > 31535999):
            //years
            $timeCount = round($nSecs / 3153600, 2) / 10;
            $sTimeSince = $timeCount . ' ' . get_string('years', 'languagelab') . ' ';
            break;

        case ($nSecs > 2627999):
            //months
            $timeCount = round($nSecs / 262800) / 10;
            $sTimeSince = $timeCount . ' ' . get_string('months', 'languagelab') . ' ';
            break;

        case ($nSecs > 604799):
            //weeks
            $timeCount = round($nSecs / 60480) / 10;
            $sTimeSince = $timeCount . ' ' . get_string('weeks', 'languagelab') . ' ';
            break;

        case ($nSecs > 86399):
            //days
            $timeCount = round($nSecs / 8640) / 10;
            $sTimeSince = $timeCount . ' ' . get_string('days', 'languagelab') . ' ';
            break;

        case ($nSecs > 3599):
            //hours
            $timeCount = round($nSecs / 360) / 10;
            $sTimeSince = $timeCount . ' ' . get_string('hours', 'languagelab') . ' ';
            break;

        case ($nSecs > 59):
            //minutes
            $timeCount = round($nSecs / 59) / 10;
            $sTimeSince = $timeCount . ' ' . get_string('minutes', 'languagelab') . ' ';
            break;

        default:
            $timeCount = $nSecs;
            $sTimeSince = $timeCount . ' ' . get_string('minutes', 'languagelab') . ' ';
            break;
    }

    $sTimeSince = get_string('agoBefore', 'languagelab') . ' ' . $sTimeSince . get_string('agoAfter', 'languagelab');

    return $sTimeSince;
}

/**
 * Format file name with strip slashes, specials chars, etc.
 * @param string $filename The filename
 * @return string The filename cleaned
 */
function format_name_download($filename)
{
    $filename = str_replace(array(' ', '/', '\\', '?', '!', '@', '#', '$', '%', '&', '*', '\'', '"', '<', '>', ':', ';'), array('_', '.', '.', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'), $filename);
    return $filename;
}

/**
 * Return the config options for the editor
 * @global stdClass $CFG
 * @param stdClass $context The context
 * @return array The config options for the editor
 */
function languagelab_get_editor_options($context)
{
    global $CFG;
    return array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1, 'changeformat' => 1, 'context' => $context, 'noclean' => 1, 'trusttext' => 0);
}

/**
 * File browsing support class
 */
class languagelab_content_file_info extends file_info_stored
{
    public function get_parent()
    {
        if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.')
        {
            return $this->browser->get_file_info($this->context);
        }
        return parent::get_parent();
    }

    public function get_visible_name()
    {
        if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.')
        {
            return $this->topvisiblename;
        }
        return parent::get_visible_name();
    }
}
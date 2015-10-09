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
include('config.php');
include('version.php');
//DO NOT MODIFY THIS FILE
//Load file that contains the server information for Moodle
$xml = simplexml_load_string(file_get_contents($CFG->xml_path));
$serverInfo = $_REQUEST['q'];
$serverAction = $_REQUEST['o'];
$submissions = $_REQUEST['s'];
$mastertrack = $_REQUEST['m'];
//given filename
if (isset($_REQUEST['f']))
{
    $filename = $_REQUEST['f'];
}
else
{
    $filename = '';
}
//security
$security = false;
if (isset($_REQUEST['r']))
{
    $security = $_REQUEST['r'];
}

if ($security == true)
{
    $url_type = 'https://';
}
else
{
    $url_type = 'http://';
}

// set count value for array
$x = 0;
$salt = '';
$path = '';
//Get XML information
foreach ($xml->children() as $child)
{
    //Create MD5 password value to compare with that sent by Moodle server
    $this_serverInfo[$x] = md5($xml->moodle[$x]->attributes()->serverAddress . $xml->moodle[$x]->attributes()->languagelabPrefix . $xml->moodle[$x]->attributes()->salt);
    if ($this_serverInfo[$x] == $serverInfo)
    {
        $serverAddress = $xml->moodle[$x]->attributes()->serverAddress;
        $languagelabPrefix = $xml->moodle[$x]->attributes()->languagelabPrefix;
        $salt = $xml->moodle[$x]->attributes()->salt;
        $path = $xml->moodle[$x]->attributes()->streamFolderPath;
        break;
    }
    $x++;
}

//what action is trying to be applied
/*
 * For the time being, only two actions exist
 * delete - delete files from the stream server
 * convert - convert files into mp3, zip and send to user. 
 */
switch ($serverAction)
{
     case md5('get_version' . $salt):
        echo $CFG->languagelab_rap_version;
        break;
    case md5('delete' . $salt):
        $submissions = json_decode($submissions);

        //Delete Master track
        if (file_exists($path . $mastertrack . '.flv'))
        {
            unlink($path . $mastertrack . '.flv');
        }
        if (file_exists($path . $mastertrack . '.flv.meta'))
        {
            unlink($path . $mastertrack . '.flv.meta');
        }
        //Go through all recordings and delete
        foreach ($submissions as $submission)
        {
            if (file_exists($path . $submission->path . '.flv'))
            {
                unlink($path . $submission->path . '.flv');
            }

            if (file_exists($path . $submission->path . '.flv,meta'))
            {
                unlink($path . $submission->path . '.flv.meta');
            }
        }
        break;
    case md5('delete_single' . $salt):
        $submissions = json_decode($submissions);

        //Go through all recordings and delete
        foreach ($submissions as $submission)
        {
            if (file_exists($path . $submission->path . '.flv'))
            {
                unlink($path . $submission->path . '.flv');
            }

            if (file_exists($path . $submission->path . '.flv.meta'))
            {
                unlink($path . $submission->path . '.flv.meta');
            }

            if (file_exists($path . $submission->path . '.mp3'))
            {
                unlink($path . $submission->path . '.mp3');
            }

            if (file_exists($path . $submission->path . '.mp3.meta'))
            {
                unlink($path . $submission->path . '.mp3.meta');
            }
        }
        break;
    case md5('convert_mp3_single' . $salt):
        //Convert File into MP3 if FFMPEG is installed
        if (shell_exec("which $CFG->conversion_tool") != '')
        {
            if (file_exists($path . $submissions . '.flv'))
            {
                $sourcefile = '"' . $path . $submissions . '.flv' . '"';
                $mp3file = $submissions . '.mp3';
                $outputfile = '"' . $path . $mp3file . '"';
                $command = $CFG->conversion_tool . ' -i ';
                shell_exec($command . $sourcefile . ' -ar 44100 -ab 64k -ac 2 ' . $outputfile);
                //Check if the mp3 file exists
                if (file_exists($path . $submissions . '.mp3'))
                {
                    echo 1;
                }
                else
                {
                    echo 0;
                }
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
        break;
    case md5('convert_mp4_single' . $salt):
        //Convert File into MP4 if FFMPEG is installed
        if (shell_exec("which $CFG->conversion_tool") != '')
        {
            if (file_exists($path . $submissions . '.flv'))
            {
                $sourcefile = '"' . $path . $submissions . '.flv' . '"';
                $mp4file = $submissions . '.mp4';
                $outputfile = '"' . $path . $mp4file . '"';
                $command = $CFG->conversion_tool . ' -i ';
                shell_exec($command . $sourcefile . '  -acodec aac -b:a 128k -vcodec libx264 -profile:v baseline -strict experimental ' . $outputfile);
                //Check if the mp4 file exists
                if (file_exists($path . $submissions . '.mp4'))
                {
                    echo 1;
                }
                else
                {
                    echo 0;
                }
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }
        break;
    case md5('upload_mp3' . $salt):
        //Save a file ont the red5 folder
        $datafile = base64_decode($_REQUEST['d']);
        $filepath = $_REQUEST['p'];
        file_put_contents($path . $filepath, $datafile);
        break;
     case md5('check_file_exist' . $salt):
        //Save a file ont the red5 folder
        $filepath = $_REQUEST['p'];
        
        $ext = '';
        $oldpath = '';
        $ext = explode(':', $filepath);
        
        if(count($ext) == 2)
        {
            $filepath = $ext[1];
            $ext = '.' . $ext[0];
        }
        else
        {
            $filepath = $ext[0];
            $ext = '.flv';
        }
        
        
        $fullpath = $path . $filepath . $ext;
        //Check if the file exists
        if (file_exists($fullpath) && is_file($fullpath))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
        break;
    case md5('download_mp3' . $salt):
        //return a file to download
        $filepath = $_REQUEST['p'];
        $newName = $_REQUEST['n'];
        if (strpos($filepath, 'mp3:') === 0)
        {
            $filepath = substr($filepath, 4) . '.mp3';
            $newName = $newName . '.mp3';
        }
        else
        {
            $filepath = $filepath . '.flv';
            $newName = $newName . '.flv';
        }
        if (file_exists($path . $filepath))
        {
            header("Content-type: octet/stream");
            header("Content-disposition: attachment; filename=" . $newName . ";");
            header("Content-Length: " . filesize($path . $filepath));
            readfile($path . $filepath);
            exit;
        }
        else
        {
            echo 0;
        }
        break;
    case md5('download_mp4' . $salt):
        //return a file to download
        $filepath = $_REQUEST['p'];
        $newName = $_REQUEST['n'];
        if (strpos($filepath, 'mp4:') === 0)
        {
            $filepath = substr($filepath, 4) . '.mp4';
            $newName = $newName . '.mp4';
        }
        elseif (strpos($filepath, 'mp3:') === 0)
        {
            $filepath = substr($filepath, 4) . '.mp3';
            $newName = $newName . '.mp3';
        }
        else
        {
            $filepath = $filepath . '.flv';
            $newName = $newName . '.flv';
        }
        if (file_exists($path . $filepath))
        {
            header("Content-type: octet/stream");
            header("Content-disposition: attachment; filename=" . $newName . ";");
            header("Content-Length: " . filesize($path . $filepath));
            readfile($path . $filepath);
            exit;
        }
        else
        {
            echo 0;
        }
        break;
    case md5('download_zip' . $salt):
        //return a zip of group of files to download
        $filepaths = explode(';', $_REQUEST['p']);
        $newNames = explode(';', $_REQUEST['n']);
        $zipName = $CFG->temp_folder . $_REQUEST['z'];

        $zip = new ZipArchive;
        if (file_exists($zipName . '.zip'))
        {
            unlink($zipName . '.zip');
        }
        if ($zip->open($zipName . '.zip', ZIPARCHIVE::CREATE) != TRUE)
        {
            exit("Cannot open <$zipName.zip>\n");
        }
        else
        {

            foreach ($filepaths as $i => $filepath)
            {
                if (strpos($filepath, 'mp3:') === 0)
                {
                    $filepath = substr($filepath, 4) . '.mp3';
                    $newNames[$i] = $newNames[$i] . '.mp3';
                }
                elseif (strpos($filepath, 'mp4:') === 0)
                {
                    $filepath = substr($filepath, 4) . '.mp4';
                    $newNames[$i] = $newNames[$i] . '.mp4';
                }
                else
                {
                    $filepath = $filepath . '.flv';
                    $newNames[$i] = $newNames[$i] . '.flv';
                }

                if (file_exists($path . $filepath))
                {
                    $zip->addFromString($newNames[$i], file_get_contents($path . $filepath));
                }
            }
            $zip->close();

            header("Content-type: octet/stream");
            header("Content-disposition: attachment; filename=" . $zipName . ".zip;");
            header("Content-Length: " . filesize($zipName . '.zip'));
            readfile($zipName . '.zip');
            exit;
        }
        break;
    case md5('move_mp3_single' . $salt):
        $newpath = $_REQUEST['n'];
        $ext = '';
        if (file_exists($path . $submissions . '.mp3'))
        {
            $ext = '.mp3';
        }
        else if (file_exists($path . $submissions . '.flv'))
        {
            $ext = '.flv';
        }
        else
        {
            return 0;
        }


        if (file_exists($path . $submissions . $ext))
        {
            $oldfile = $path . $submissions . $ext;
            $newfile = $path . $newpath . $ext;

            $folders = explode('/', $submissions);
            //Delete the last one because it is the file
            unset($folders[count($folders) - 1]);

            $concatFolders = $path;
            foreach ($folders as $folder)
            {
                $concatFolders .= $folder . '/';
                if (!file_exists($concatFolders) || !is_dir($concatFolders))
                {
                    mkdir($concatFolders);
                }
            }

            if (copy($oldfile, $newfile))
            {
                unlink($oldfile);
            }

            //Check if the mp3 file exists
            if (file_exists($newfile) && is_file($newfile))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }

        break;
    case md5('move_single' . $salt):
        $newpath = $_REQUEST['n'];
        $keeporiginal = true;
        $ext = '';
        $oldpath = '';
        $ext = explode(':', $submissions);
        
        if(isset($_REQUEST['k']))
        {
            if($_REQUEST['k'] == 1)
            {
                $keeporiginal = true;
            }
            else if($_REQUEST['k'] == 0)
            {
                $keeporiginal = false;
            }
        }
        if(count($ext) == 2)
        {
            $oldpath = $ext[1];
            $ext = '.' . $ext[0];
        }
        else
        {
            $oldpath = $ext[0];
            $ext = '.flv';
        }
        $newpath = explode(':', $newpath);
        if(count($newpath) == 2)
        {
            $newpath = $newpath[1];
        }
        else
        {
            $newpath = $newpath[0];
        }
        
        if (file_exists($path . $oldpath . $ext))
        {
            $oldfile = $path . $oldpath . $ext;
            $newfile = $path . $newpath . $ext;

            $folders = explode('/', $newpath);
            //Delete the last one because it is the file
            unset($folders[count($folders) - 1]);

            $concatFolders = $path;
            foreach ($folders as $folder)
            {
                $concatFolders .= $folder . '/';
                if (!file_exists($concatFolders) || !is_dir($concatFolders))
                {
                    mkdir($concatFolders, 0777, true);
                }
            }

            if (copy($oldfile, $newfile))
            {
                if(!$keeporiginal)
                {
                    unlink($oldfile);
                }
            }

            //Check if the mp3 file exists
            if (file_exists($newfile) && is_file($newfile))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        else
        {
            echo 0;
        }

        break;
    //Backup a language lab activity
    case md5('backup' . $salt);
        $submissions = json_decode($submissions);

        $zip = new ZipArchive;
        if ($zip->open($filename . '.zip', ZIPARCHIVE::CREATE) != TRUE)
        {
            exit("Cannot open <$filename.zip>\n");
        }
        else
        {

            //add master track
            //Create folder inside archive for master track
            echo "<ul>";
            if (file_exists($path . $mastertrack . '.flv'))
            {
                echo "<li>$mastertrack.flv added to archive</li>";
                $zip->addFile($path . $mastertrack . '.flv', 'Master Track/' . $mastertrack . '.flv');
                $xml_mt = "<master_track location='Master Track' />\n";
                //Convert File into MP3 if FFMPEG is installed
                if (file_exists($CFG->conversion_tool_path . $CFG->conversion_tool))
                {
                    $sourcefile = '"' . $path . $mastertrack . '.flv' . '"';
                    $mp3file = $mastertrack . '.mp3';
                    $outputfile = '"' . $CFG->temp_folder . $mp3file . '"';
                    $command = $CFG->conversion_tool_path . $CFG->conversion_tool . ' -i ';
                    shell_exec($command . $sourcefile . ' ' . $outputfile);
                    $zip->addFile($CFG->temp_folder . $mp3file, 'Master Track/' . $mp3file);
                }
            }

            //add all student recordings to zip file
            $x = 0;
            foreach ($submissions as $submission)
            {
                //Create user folder inside archive
                $user = $submission->username;

                if (file_exists($path . $submission->path . '.flv'))
                {
                    echo "<li>$submission->path.flv added to archive</li>";
                    $zip->addFile($path . $submission->path . '.flv', $user . '/' . $submission->path . '.flv');
                    $xml_submission = $xml_submission . "<submission originalName='$submission->path.flv' location='$user'/>\n";
                    //Convert File into MP3 if FFMPEG is installed
                    if (file_exists($CFG->conversion_tool_path . $CFG->conversion_tool))
                    {
                        $sourcefile = '"' . $path . $submission->path . '.flv' . '"';
                        $mp3file = $submission->path . '.mp3';
                        $outputfile = '"' . $CFG->temp_folder . $mp3file . '"';
                        $command = $CFG->conversion_tool_path . $CFG->conversion_tool . ' -i ';
                        shell_exec($command . $sourcefile . ' ' . $outputfile);
                        //Add file to zip archive
                        $zip->addFile($CFG->temp_folder . $mp3file, $user . '/' . $mp3file);
                    }
                }

                $x++;
            }
            echo "</ul>";
            //Create temp XML File
            $temp_file_name = time();
            $xml = "<audio>" . "\n" . $xml_mt . $xml_submission . "</audio>";
            $xml = new SimpleXMLElement($xml);
            $xml->asXML($CFG->temp_folder . $temp_file_name . '.xml');
            $zip->addFile($CFG->temp_folder . $temp_file_name . '.xml', 'xml/export.xml');
            $zip->close();
            //remove all files in temp folder
            foreach (glob($CFG->temp_folder . '*.*') as $file)
            {
                unlink($file);
            }

            echo "<br><a href=\"$url_type$serverAddress/$filename.zip\"><h1>download archive</h1></a>";
        }
        break;
    case md5('create_folder' . $salt):
        $folder = $path . $submissions;
        
        if (!file_exists($folder) || !is_dir($folder))
        {
            mkdir($folder, 0777, true);
        }
        if (file_exists($folder) && is_dir($folder))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
        break;
}
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
//DO NOT MODIFY THIS FILE
include('config.php');
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
//Get XML information
foreach ($xml->children() as $child)
{
    //Create MD5 password value to compare with that sent by Moodle server
    $this_serverInfo[$x] = md5($xml->moodle[$x]->attributes()->serverAddress . $xml->moodle[$x]->attributes()->languagelabPrefix . $xml->moodle[$x]->attributes()->salt);
    if ($this_serverInfo[$x] == $serverInfo)
    {
        $works[$x] = 'true';
        break;
    }
    $x++;
}

print "<br>Your Apache/PHP server is working, now checking RAP configuration...";
if (count($works) > 0)
{
    print "<br>Congratulations, your RAP configuration is working!";
}
else
{
    print "<br>Sorry, there is a problem with your configuration. Check your XML file: The serverAddress, languagelabPrefix and salt must be identical from the moodle settings languagelab_red5server, languagelab_prefix and languagelab_salt.";
}

print "<br>Checking the ffmpeg availability...";
if (shell_exec("which $CFG->conversion_tool") != '')
{
    print "<br>Command \"$CFG->conversion_tool\" founded and executable";
    //Todo Check FFMPEG codecs availability
}
else
{
    print "<br>Command \"$CFG->conversion_tool\" NOT found or not executable. Please check the config.php file to change the conversion_tool command or install it.";
}
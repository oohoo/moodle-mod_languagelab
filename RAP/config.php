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
//Load file that contains the server information for Moodle
$CFG = new stdClass();
//Change this value to the root folder of your host
$CFG->rootdir = '/var/www/';
//Change this value to point to your config.xml file.
$CFG->xml_path = "/var/config.xml";
//Change this value to point to a temp folder. Make sure write read permissions are set properly
$CFG->temp_folder = "/var/tmp/";
//Path to ffmpeg or avcnonv for mp3 conversion. If empty, you will not be able to convert flv to mp3
//Usually found in /usr/bin/
$CFG->conversion_tool_path = "/usr/bin/";
//Tool used to convert 
//Use either ffmpeg or avconv
$CFG->conversion_tool = 'ffmpeg';
//Deprecated. Keeping this config value for backwards compatibility. Path to ffmpeg for mp3 conversion. If empty, you will not be able to convert flv to mp3
//Usually found in /usr/bin/
$CFG->ffmpeg = "/usr/bin/";
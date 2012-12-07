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


global $CFG, $DB, $PAGE, $OUTPUT;


require_login(1, true);

/// Print the page header

$PAGE->set_url('/mod/languagelab/playeroptions.php');
$PAGE->set_title('');
$PAGE->set_heading('');

//print_object($context);
// Output starts here

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  dir="ltr" lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta name="author" content="Nicolas Bretin - oohoo.biz" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css" />
        <style>
        html{width:430px;}
        #divPlayerOptionsText{text-align:left;}
        </style>
    </head>    
    <body style="font-family: Helvetica,Arial,sans-serif;font-size:90%; width: 430px; text-align:left; margin: 0px;">
';


//Definition of the main variables kept in JS
echo '<script type="text/javascript">
    var playerOptions;
    </script>';

echo '<div align=\'center\'>';


echo '<script type="text/javascript">';
echo '  var playeroptionsBtnOk = "' . get_string('playeroptionsBtnOk', 'languagelab') . '";';
echo '</script>';

//Load the flash options menu
echo '
<div id="divPlayerOptionsPageOptions" title="' . get_string('titlePlayerOptions', 'languagelab') . '">
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

echo '</div>';

// Finish the page
echo '
    </body>
</html>';
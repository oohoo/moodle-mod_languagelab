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
$courseid = optional_param('course', 0, PARAM_INT); // Course id
$all = optional_param('all', 0, PARAM_INT); // All activities

$keep_originals = optional_param('keep_originals', 1, PARAM_INT); // Keep previous files, just do a copy


global $CFG, $DB, $PAGE;

require_login(null, false);

//Replace get_context_instance by the class for moodle 2.6+
if(class_exists('context_module'))
{
    $context = context_system::instance();
}
else
{
    $context = get_context_instance(CONTEXT_SYSTEM);
}

$PAGE->set_context($context);

$PAGE->set_url('/mod/languagelab/admin-migration-reorder.php');
$PAGE->set_title('Language Lab Migration reorder');
$PAGE->set_heading('Language Lab Migration reorder');

// Output starts here
echo $OUTPUT->header();

/// Print the main part of the page
echo $OUTPUT->box_start();

if (!is_siteadmin($USER))
{
    error('Only administrators can access this page');
}
else
{
    if($id != 0 || $courseid != 0 || $all != 0)
    {
        migration_all_recordings_new_folder($id, $courseid, $keep_originals);
    }
    else
    {
        echo '<b>WARNING</b> This process will migrate all language labg recordings form their current path to new configuration. <br/>';
        echo 'First update your configuration in the language lab settings. <br/>';
        echo 'Then you can:<br/>';
        echo '<ul>';
        echo '<li>Migrate only one language lab by adding parameter "id" with the ID of the activity (like in the view.php). /mod/languagelab/admin-migration-reorder.php?id=MYACTIVITY </li>';
        echo '<li>Migrate all activities in a course by adding parameter "course" with the ID of the course. /mod/languagelab/admin-migration-reorder.php?course=MYCOURSE </li>';
        echo '<li>Migrate <b>all activities</b> by adding parameter "all" with value 1. /mod/languagelab/admin-migration-reorder.php?all=1 </li>';
        echo '<li>Just Copy the files by adding parameter "keep_originals" with value 1. /mod/languagelab/admin-migration-reorder.php?all=1&keep_originals=1 </li>';
        echo '<li>Move the files by adding parameter "keep_originals" with value 0. /mod/languagelab/admin-migration-reorder.php?all=1&keep_originals=0 </li>';
        echo '</ul>';
    }
}

echo $OUTPUT->box_end();

// Finish the page
echo $OUTPUT->footer();
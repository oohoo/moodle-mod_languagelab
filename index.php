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

$id = required_param('id', PARAM_INT);   // course

if (!$course = $DB->get_record('course', array('id' => $id)))
{
    error('Course ID is incorrect');
}

require_course_login($course);

//Only for Moodle < 2.7
if(!function_exists('moodle_major_version') || moodle_major_version() < '2.7')
{
    add_to_log($course->id, 'languagelab', 'view all', "index.php?id=$course->id", '');
}
else
{
    //TODO Log for Moodle 2.7+
}

/// Print the header

$PAGE->set_url('/mod/languagelab/view.php', array('id' => $id));
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->shortname);

echo $OUTPUT->header();

/// Get all the appropriate data

if (!$languagelabs = get_all_instances_in_course('languagelab', $course))
{
    echo $OUTPUT->heading(get_string('nolanguagelabs', 'languagelab'), 2);
    echo $OUTPUT->continue_button("view.php?id=$course->id");
    echo $OUTPUT->footer();
    die();
}

/// Print the list of instances (your module will probably extend this)

$timenow = time();
$strname = get_string('name');
$strweek = get_string('week');
$strtopic = get_string('topic');

if ($course->format == 'weeks')
{
    $table->head = array($strweek, $strname);
    $table->align = array('center', 'left');
}
else if ($course->format == 'topics')
{
    $table->head = array($strtopic, $strname);
    $table->align = array('center', 'left', 'left', 'left');
}
else
{
    $table->head = array($strname);
    $table->align = array('left', 'left', 'left');
}

foreach ($languagelabs as $languagelab)
{
    if (!$languagelab->visible)
    {
        //Show dimmed if the mod is hidden
        $link = '<a class="dimmed" href="view.php?id=' . $languagelab->coursemodule . '">' . format_string($languagelab->name) . '</a>';
    }
    else
    {
        //Show normal if the mod is visible
        $link = '<a href="view.php?id=' . $languagelab->coursemodule . '">' . format_string($languagelab->name) . '</a>';
    }

    if ($course->format == 'weeks' or $course->format == 'topics')
    {
        $table->data[] = array($languagelab->section, $link);
    }
    else
    {
        $table->data[] = array($link);
    }
}

echo $OUTPUT->heading(get_string('modulenameplural', 'languagelab'), 2);
print_table($table);

/// Finish the page

echo $OUTPUT->footer();
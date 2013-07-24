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
require_once($CFG->dirroot . '/mod/languagelab/backup/moodle2/backup_languagelab_stepslib.php'); // Because it exists (must)
require_once($CFG->dirroot . '/mod/languagelab/backup/moodle2/backup_languagelab_settingslib.php'); // Because it exists (optional)

/**
 * choice backup task that provides all the settings and steps to perform one
 * complete backup of the activity
 */
class backup_languagelab_activity_task extends backup_activity_task
{

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings()
    {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps()
    {
        $this->add_step(new backup_languagelab_activity_structure_step('languagelab_structure', 'languagelab.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content)
    {
        global $CFG;

        $base = preg_quote($CFG->wwwroot,"/");

        // Link to page view by moduleid
        $search="/(".$base."\/mod\/languagelab\/view.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@MODLANGUAGELABVIEWBYID*$2@$', $content);
        
        // Link to page view by moduleid
        $search="/(".$base."\/mod\/languagelab\/classmonitor.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@MODLANGUAGELABCLASSMONITORBYID*$2@$', $content);

        return $content;
    }

}
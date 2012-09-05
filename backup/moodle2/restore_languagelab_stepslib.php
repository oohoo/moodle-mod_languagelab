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
/**
 * Define all the restore steps that will be used by the restore_choice_activity_task
 */

/**
 * Structure step to restore one choice activity
 */
class restore_languagelab_activity_structure_step extends restore_activity_structure_step
{

    protected function define_structure()
    {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('languagelab', '/activity/languagelab');
        if ($userinfo)
        {
            $paths[] = new restore_path_element('languagelab_submission', '/activity/languagelab/languagelab_submissions/languagelab_submission');
            $paths[] = new restore_path_element('languagelab_student_eval', '/activity/languagelab/languagelab_student_evals/languagelab_student_eval');
        }

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_languagelab($data)
    {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the tab record
        $newitemid = $DB->insert_record('languagelab', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    protected function process_languagelab_submission($data)
    {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->languagelab = $this->get_new_parentid('languagelab');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record('languagelab_submissions', $data);
        //$this->set_mapping('languagelab_submissions', $oldid, $newitemid, false); //has related files
    }

    protected function process_languagelab_student_eval($data)
    {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->languagelab = $this->get_new_parentid('languagelab');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newitemid = $DB->insert_record('languagelab_student_eval', $data);
        //$this->set_mapping('languagelab_student_eval', $oldid, $newitemid, false); //has related files
    }

    protected function after_execute()
    {
        global $DB;
        // Add tab related files where itemname = languagelab (taken from $this->set_mapping)
        $this->add_related_files('mod_languagelab', 'content', 'languagelab');
        $this->add_related_files('mod_languagelab', 'mastertrack', 'languagelab');
    }

}

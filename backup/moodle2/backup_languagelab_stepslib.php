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
 * Define all the backup steps that will be used by the backup_choice_activity_task
 */
class backup_languagelab_activity_structure_step extends backup_activity_structure_step
{

    protected function define_structure()
    {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $languagelab = new backup_nested_element('languagelab', array('id'), array('name', 'course', 'description', 'contentformat', 
            'timedue', 'timeavailable', 'grade', 'recording_timelimit', 'attempts', 'video', 'use_grade_book', 'group_type', 
            'master_track', 'master_track_recording', 'us_mp3', 'student_delete_recordings', 'prev_next_lab', 'fullscreen_student', 'simplified_interface_student',
            'timemodified'));

        //languaglab submissions table
        $languagelab_submissions = new backup_nested_element('languagelab_submissions');

        $languagelab_submission = new backup_nested_element('languagelab_submission', array('id'), array('userid',
                    'groupid', 'path', 'label', 'message','parentnode', 'timecreated', 'timemodified'));

        //languagelab student eval table
        $languagelab_student_evals = new backup_nested_element('languagelab_student_evals');

        $languagelab_student_eval = new backup_nested_element('languagelab_student_eval', array('id'), array('userid',
                    'correctionnotes', 'grade', 'teacher', 'timemarked', 'timecreated', 'timemodified'));

        // Build the tree
        $languagelab->add_child($languagelab_submissions);
        $languagelab_submissions->add_child($languagelab_submission);

        $languagelab->add_child($languagelab_student_evals);
        $languagelab_student_evals->add_child($languagelab_student_eval);



        // Define sources
        $languagelab->set_source_table('languagelab', array('id' => backup::VAR_ACTIVITYID));

        $languagelab_submission->set_source_table('languagelab_submissions', array('languagelab' => backup::VAR_PARENTID));

        $languagelab_student_eval->set_source_table('languagelab_student_eval', array('languagelab' => backup::VAR_PARENTID));

        // Define id annotations
        $languagelab_submission->annotate_ids('user', 'userid');
        $languagelab_student_eval->annotate_ids('user', 'userid');


        // Define file annotations
        $languagelab->annotate_files('mod_languagelab', 'description', null);
        $languagelab->annotate_files('mod_languagelab', 'content', 'id');
        $languagelab->annotate_files('mod_languagelab', 'mastertrack', 'id');

        // Return the root element (languagelab), wrapped into standard activity structure
        return $this->prepare_activity_structure($languagelab);
    }

}
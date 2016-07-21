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
defined('MOODLE_INTERNAL') || die; 

function xmldb_languagelab_upgrade($oldversion = 0)
{
    global $CFG, $THEME, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2011021200)
    {
        // Define field contentformat to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('contentformat', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'description');

        // Conditionally launch add field contentformat
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011021200, 'languagelab');
    }

    if ($oldversion < 2011031800)
    {
        // Define field contentformat to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('contentformat', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'description');

        // Conditionally launch add field contentformat
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011031800, 'languagelab');
    }

    if ($oldversion < 2011041901)
    {
        // Define field id to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('use_grade_book', XMLDB_TYPE_INTEGER, '1', null, null, null, null, null);

        // Conditionally launch add field id
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011041901, 'languagelab');
    }
    if ($oldversion < 2011050900)
    {
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011050900, 'languagelab');
    }

    if ($oldversion < 2011051100)
    {
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011051100, 'languagelab');
    }

    if ($oldversion < 2011052600)
    {
        // Define field video to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('video', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'attempts');

        // Conditionally launch add field video
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011052600, 'languagelab');
    }

    if ($oldversion < 2011052601)
    {
        // Define field group_type to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('group_type', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'use_grade_book');

        // Conditionally launch add field group_type
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011052601, 'languagelab');
    }

    if ($oldversion < 2011080800)
    {
        //Fixed undefined variables
        //Fixed $available in view.php
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011080800, 'languagelab');
    }
    if ($oldversion < 2011082600)
    {
        // Define field master_track to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('master_track', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'group_type');

        // Conditionally launch add field master_track
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011082600, 'languagelab');
    }
    if ($oldversion < 2011082700)
    {
        //Activated Master track feature
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011082700, 'languagelab');
    }
    if ($oldversion < 2011082900)
    {
        //Fixed backup_steps (missing fields)
        //Updated LanguageLabCT.swf
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011082900, 'languagelab');
    }
    if ($oldversion < 2011113000)
    {
        //Rebuilt both SWF. No longer need XMLSocket server
        //Updated LanguageLabCT.swf
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011113000, 'languagelab');
    }
    if ($oldversion < 2011120500)
    {
        //Removed XML Socket settings
        //Updated LanguageLabCT.swf
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011120500, 'languagelab');
    }
    if ($oldversion < 2011121200)
    {
        // Define field master_track_recording to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('master_track_recording', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'master_track');

        // Conditionally launch add field master_track_recording
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011121200, 'languagelab');
    }
    if ($oldversion < 2011121500)
    {
        // Define field use_mp3 to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('use_mp3', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'master_track_recording');

        // Conditionally launch add field use_mp3
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011121500, 'languagelab');
    }
    if ($oldversion < 2011121701)
    {
        //Added Red5 Adapter Plugin File name admin setting
        //Added Security level for the RAP Server 
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011121701, 'languagelab');
    }
    if ($oldversion < 2012030800)
    {
        //Added scrubbing feature
        //New nonStreamingBasePath Config
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012030800, 'languagelab');
    }
    if ($oldversion < 2012031200)
    {
        //Added RAP security for testing. Must also create manage capability 

        $capabilities = array(
            'mod/languagelab:manage' => array(
                'captype' => 'write',
                'contextlevel' => CONTEXT_MODULE,
                'archetypes' => array(
                    'teacher' => CAP_PREVENT,
                    'student' => CAP_PREVENT,
                    'editingteacher' => CAP_PREVENT,
                    'manager' => CAP_ALLOW
                )
            ),
        );

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012031200, 'languagelab');
    }

    if ($oldversion < 2012031201)
    {
        // Changing the default of field grade on table languagelab_student_eval to drop it
        $table = new xmldb_table('languagelab_student_eval');
        $field = new xmldb_field('grade', XMLDB_TYPE_INTEGER, '11', null, null, null, null, 'correctionnotes');

        // Launch change of default for field grade
        $dbman->change_field_default($table, $field);

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012031201, 'languagelab');
    }

    if ($oldversion < 2012051800)
    {
        // Define table languagelab_user_live to be created
        $table = new xmldb_table('languagelab_user_live');

        // Adding fields to table languagelab_user_live
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('languagelab', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('live', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table languagelab_user_live
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for languagelab_user_live
        if (!$dbman->table_exists($table))
        {
            $dbman->create_table($table);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012051800, 'languagelab');
    }

    if ($oldversion < 2012051801)
    {
        // Define field timemodified to be added to languagelab_user_live
        $table = new xmldb_table('languagelab_user_live');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '20', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'live');

        // Conditionally launch add field timemodified
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012051801, 'languagelab');
    }

    if ($oldversion < 2012051802)
    {
        // Define table languagelab_user_event to be created
        $table = new xmldb_table('languagelab_user_event');

        // Adding fields to table languagelab_user_event
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('languagelab', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
        $table->add_field('type', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null);
        $table->add_field('data', XMLDB_TYPE_TEXT, 'big', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '20', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);

        // Adding keys to table languagelab_user_event
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('languagelab_ix', XMLDB_KEY_FOREIGN, array('languagelab'), 'languagelab', array('id'));

        // Conditionally launch create table for languagelab_user_event
        if (!$dbman->table_exists($table))
        {
            $dbman->create_table($table);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012051802, 'languagelab');
    }

    if ($oldversion < 2012052200)
    {
        // Changing type of field type on table languagelab_user_event to char
        $table = new xmldb_table('languagelab_user_event');
        $field = new xmldb_field('type', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'userid');

        // Launch change of type for field type
        $dbman->change_field_type($table, $field);

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012052200, 'languagelab');
    }

    if ($oldversion < 2012080400)
    {
        //Add addinstance capability
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012080400, 'languagelab');
    }

    if ($oldversion < 2012092701)
    {
        // Update the settings
        // Add the video !!
        upgrade_mod_savepoint(true, 2012092701, 'languagelab');
    }

    if ($oldversion < 2012101700)
    {
        //+Add student delete recordings rights
        //+Gradebook corrections
        //+IE flash Player options patch
        // Define field student_delete_recordings to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('student_delete_recordings', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'use_mp3');

        // Conditionally launch add field student_delete_recordings
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012101700, 'languagelab');
    }

    if ($oldversion < 2012120700)
    {
        //+Corrections
        //+Remove PHP end tag in files
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012120700, 'languagelab');
    }

    if ($oldversion < 2012121000)
    {
        //+Corrections
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012121000, 'languagelab');
    }
    
    if ($oldversion < 2012121900)
    {
        //+ Add functions for the RESET mode
        //+ Correct problems with the flash options and Firefox 17
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2012121900, 'languagelab');
    }
    
    if ($oldversion < 2013010700)
    {
        //+ Add a correction to the Edit form
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013010700, 'languagelab');
    }
    
    if ($oldversion < 2013020802)
    {
        //+ Add some tests to the RAP in order to help users to configure
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013020802, 'languagelab');
    }
    
    if ($oldversion < 2013021200)
    {
        //+ Suppression of a string in the language files
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013021200, 'languagelab');
    }
    
    if ($oldversion < 2013022000)
    {
        //+ One more correction on the language files!
        //+ Add a debug to display if jQuery or a linked lib is not available
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013022000, 'languagelab');
    }
    
    if ($oldversion < 2013071000)
    {
        //+Add prev next button
        //+Add fullscreen option student
        // Define field student_delete_recordings to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('prev_next_lab', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'student_delete_recordings');
        // Conditionally launch add field student_delete_recordings
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('fullscreen_student', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'prev_next_lab');
        // Conditionally launch add field student_delete_recordings
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013071000, 'languagelab');
    }
    
    if ($oldversion < 2013071800)
    {
        //+ Add RAP test control
        //+ Add RAP version
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013071800, 'languagelab');
    }
    
    if ($oldversion < 2013072200)
    {
        //+Add control when student record without saving
        //+Add Simplified interface for student
        // Define field student_delete_recordings to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('simplified_interface_student', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'fullscreen_student');
        // Conditionally launch add field student_delete_recordings
        if (!$dbman->field_exists($table, $field))
        {
            $dbman->add_field($table, $field);
        }
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013072200, 'languagelab');
    }
    
    if ($oldversion < 2013072400)
    {
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013072400, 'languagelab');
    }
    
    if ($oldversion < 2013093000)
    {
        //+patch install.xml
        //+patch filepicker over dialog
        //+patch linux flash detection
        //+patch dialog overlay limit
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013093000, 'languagelab');
    }
    
    if ($oldversion < 2013110400)
    {
        //patch raptest.php
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013110400, 'languagelab');
    }
    
    if ($oldversion < 2013112500)
    {
        //Patch in the flash for the HTTPS
        //Patch RAP for the mastertrack upload
        //Add ffmpeg test to the RAP test
        //remove settings NB max connections
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013112500, 'languagelab');
    }
    
    if ($oldversion < 2013112600)
    {
        //Patch 2 RAP for the mastertrack upload
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013112600, 'languagelab');
    }
    
    if ($oldversion < 2013112700)
    {
        //Patch to force the sort on the students list
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2013112700, 'languagelab');
    }
    
    if ($oldversion < 2014020300)
    {
        // Patch for 2.6
        // Add the due date to the calendar
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014020300, 'languagelab');
    }
    
    if ($oldversion < 2014022500)
    {
        // Patch a problem with the feedbacks
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014022500, 'languagelab');
    }
    
    if ($oldversion < 2014022501)
    {
        // Patch the problem when the microphone/camera is not plugged
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014022501, 'languagelab');
    }
    
    if ($oldversion < 2014061100)
    {
        // Patch a probleme with the calendar update and Moodle < 2.6
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014061100, 'languagelab');
    }
    
    if ($oldversion < 2014071700)
    {
        // Update for Moodle 2.7
        // Patch on JSTREE
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014071700, 'languagelab');
    }
    
    if ($oldversion < 2014091600)
    {
        // Add few patch for PostgreSQL and for theme compatibility.
        // Add a migration page for moving files on the streaming server.
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014091600, 'languagelab');
    }
    
    if ($oldversion < 2014091900)
    {
        // Update the migration tool
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014091900, 'languagelab');
    }
    
    if ($oldversion < 2014101700)
    {
        // Add a verification when the mastertrack does not exist - RAP
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014101700, 'languagelab');
    }
    
    if ($oldversion < 2014112600)
    {
        // Add a verification when the mastertrack does not exist - RAP
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2014112600, 'languagelab');
    }
    
    if ($oldversion < 2015100900)
    {
        // Fix Issue in RAP for create folder
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2015100900, 'languagelab');
    }
    
    if ($oldversion < 2016072100)
    {
        // Upgrade Moodle 3.0
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2016072100, 'languagelab');
    }
    
    return;
}
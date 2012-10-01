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

    return;
}
?>

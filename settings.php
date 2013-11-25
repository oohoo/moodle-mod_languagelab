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

$settings->add(new admin_setting_heading('languagelab_header',  '',  get_string('adminconfig_header', 'languagelab', $CFG->wwwroot.'/mod/languagelab/raptest.php')));
$settings->add(new admin_setting_configtext('languagelab_red5serverprotocol', get_string('red5serverprotocol', 'languagelab'), get_string('red5serverprotocol_help', 'languagelab'), 'rtmp', PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_red5server', get_string('red5server', 'languagelab'), get_string('red5server_help', 'languagelab'), '', PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_red5serverfolder', get_string('red5serverfolder', 'languagelab'), get_string('red5serverfolder_help', 'languagelab'), 'oflaDemo', PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_folder', get_string('folder', 'languagelab'), get_string('folder_help', 'languagelab'), 'moodle', PARAM_TEXT));
$settings->add(new admin_setting_configtext('languagelab_prefix', get_string('prefix', 'languagelab'), get_string('prefix_help', 'languagelab'), 'mdl', PARAM_RAW));
$settings->add(new admin_setting_configcheckbox('languagelab_stealthMode', get_string('stealthMode', 'languagelab'), get_string('stealthMode_help', 'languagelab'), '0', 1, 0));
$settings->add(new admin_setting_configtext('languagelab_adapter_server', get_string('adapter_server', 'languagelab'), get_string('adapter_server_help', 'languagelab'), '', PARAM_TEXT));
$settings->add(new admin_setting_configtext('languagelab_adapter_file', get_string('adapter_file', 'languagelab'), get_string('adapter_file_help', 'languagelab'), 'adapter', PARAM_TEXT));
$settings->add(new admin_setting_configcheckbox('languagelab_adapter_access', get_string('adapter_access', 'languagelab'), get_string('adapter_access_help', 'languagelab'), '0', 1, 0));
$settings->add(new admin_setting_configtext('languagelab_salt', get_string('salt', 'languagelab'), get_string('salt_help', 'languagelab'), '', PARAM_TEXT));
$settings->add(new admin_setting_configtext('languagelab_secondsRefreshHistory', get_string('secondsRefreshHistory', 'languagelab'), get_string('secondsRefreshHistory_help', 'languagelab'), '30000', PARAM_INT));
$settings->add(new admin_setting_configtext('languagelab_secondsRefreshClassmonitor', get_string('secondsRefreshClassmonitor', 'languagelab'), get_string('secondsRefreshClassmonitor_help', 'languagelab'), '5000', PARAM_INT));
$settings->add(new admin_setting_configtext('languagelab_secondsRefreshStudentView', get_string('secondsRefreshStudentView', 'languagelab'), get_string('secondsRefreshStudentView_help', 'languagelab'), '5000', PARAM_INT));

//Removed parameters since they are not used
//$settings->add(new admin_setting_configtext('languagelab_max_users', get_string('max_users', 'languagelab'), get_string('max_users_help', 'languagelab'), '25', PARAM_INT));

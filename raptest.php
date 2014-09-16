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
require_once '../../config.php';
require_once 'locallib.php';
require_once 'lib.php';
require_once 'RAP/version.php';


require_login(1, false);

global $CFG, $PAGE, $OUTPUT;
//Replace get_context_instance by the class for moodle 2.6+
if(class_exists('context_system'))
{
    $context = context_system::instance();
}
else
{
    $context = get_context_instance(CONTEXT_SYSTEM);
}

$PAGE->set_url('/admin/settings.php', array('section' => 'modsettinglanguagelab'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title('RAP test');
$PAGE->set_heading('RAP test');
$PAGE->set_context($context);

if (has_capability('mod/languagelab:manage', $context, null, true))
{
    echo $OUTPUT->header();
    
    echo '<h1>Language Lab configuration checker</h1>';
    //Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file) && isset($CFG->languagelab_adapter_server))
    {
        if ($CFG->languagelab_adapter_file == '' || $CFG->languagelab_adapter_server == '')
        {
            echo 'Some configuration is missing. Have you filled in the fields languagelab_adapter_server and languagelab_adapter_file in the Language Lab settings ?';
        }
        else
        {
            $Red5Server = $CFG->languagelab_red5server;
            $RapServer = $CFG->languagelab_adapter_server;
            $RapFile = $CFG->languagelab_adapter_file . '.php';
            $RapPath = dirname($RapFile);
            if ($RapPath == '.' || $RapPath == '')
            {
                $RapPath = '';
            }
            $RapPath .= '/';
            $prefix = $CFG->languagelab_prefix;
            $salt = $CFG->languagelab_salt;
            //RAP security
            if ($CFG->languagelab_adapter_access == true)
            {
                $security = 'https://';
            }
            else
            {
                $security = 'http://';
            }
            $url = "$security$RapServer{$RapPath}test.php";
            if (isDomainAvailable($url))
            {
                echo 'Site is available';
            }
            else
            {
                echo 'Site is unavailable, there is probably a problem with the path to the RAP adapter (languagelab_adapter_file), or with your Apache/PHP server hosting the RAP or is this server have a default gateway well configured?';
            }
            echo '<br/>--------------------------------------------------------------<br/>';
            //Encrypt information
            $q = md5($Red5Server . $prefix . $salt);
            $o = md5('raptest' . $salt);

            $vars = "q=$q&o=$o";

            //Send request to red5 server using curl
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            curl_close($ch);

            echo $result;

            
            if ($result !== false)
            {
                echo '<br/>--------------------------------------------------------------<br/>';
                //Check the version of the adapter now
                //Create the folder on the RTMP server
                $adapter_version = languagelab_adapter_call('get_version', '');
                echo 'Checking the version of the RAP...<br/>';
                if ($adapter_version != $CFG->languagelab_rap_version)
                {
                    echo 'The version of the RAP files is not the one expected by the language lab. Please check you have updated the version or your RAP files.';
                    echo '<br/>Version expected = ' . $CFG->languagelab_rap_version;
                    echo '<br/>Version found = ' . $adapter_version;
                }
                else
                {
                    echo 'RAP version version OK.';
                }
            }
            echo '<br/>--------------------------------------------------------------<br/>';
            //Check RTMP port open
            $rtmp_port = 1935;
            $rtmp_ip = $CFG->languagelab_red5server;
            if (strpos($rtmp_ip, ':') !== false)
            {
                $rtmp_ip = explode(':', $rtmp_ip);
                $rtmp_port = $rtmp_ip[1];
                $rtmp_ip = $rtmp_ip[0];
            }

            $errno = '';
            $errstr = '';
            $fp = @fsockopen($rtmp_ip, $rtmp_port, $errno, $errstr, 15);

            if (!$fp)
            {
                echo "<br />The streaming server is not accessible or not started: $errstr ($errno)<br />\n";
            }
            else
            {
                fclose($fp);
                echo '<br/>The streaming server is running and available.';
            }
        }
    }
    echo $OUTPUT->footer();
}
else
{
    redirect($CFG->wwwroot, get_string('norappermission', 'mod_languagelab'), 5);
}

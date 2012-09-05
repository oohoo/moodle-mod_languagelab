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
include('../../config.php');
include('locallib.php');


require_login(1, false);

global $CFG, $PAGE, $OUTPUT;
$context = get_context_instance(CONTEXT_SYSTEM);

$PAGE->set_url('/mod/languagelab/raptest.php', array('id' => 1));
$PAGE->set_title('RAP test');
$PAGE->set_heading('RAP test');
$PAGE->set_context($context);

if (has_capability('mod/languagelab:manage', $context, null, true))
{

    echo $OUTPUT->header();

//Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file))
    {
        //Let's delete all files on the Red5 Server
        $Red5Server = $CFG->languagelab_red5server;
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
        $url = "$security$Red5Server/test.php";
        if (isDomainAvailible($url))
        {
            echo 'Site is available';
        }
        else
        {
            echo 'There is an error in your configuration';
        }

        //Encrypt information
        $q = md5($Red5Server . $prefix . $salt);

        $vars = "q=$q";

        //Send request to red5 server using curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);

        $result = curl_exec($ch);
    }
    echo $OUTPUT->footer();
}
else
{

    redirect($CFG->wwwroot, get_string('norappermission', 'mod_languagelab'), 5);
}
?>

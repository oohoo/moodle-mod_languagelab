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
foreach (glob($CFG->rootdir . '*.zip') as $file)
{
    $lastchanged = filectime($file);
    $difference = time() - $lastchanged;
    $expires = 450000; //5 days
    //Delete files if older than 5 days
    if ($difference >= $expires)
    {
        unlink($file);
        echo 'Deleted ' . $file . '<br>';
    }
    else
    {
        echo 'No files to delete';
    }
}
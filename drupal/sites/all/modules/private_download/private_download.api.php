<?php

/**
 * @file
 * Hooks that can be implemented by other modules to extend Private Download.
 */

/**
 * Alter permission to access private files.
 *
 * Note that altering permission will have effect only within the limits of
 * option chosen by admin for "Allow/Deny priority". By default, for security 
 * reasons, this setting is set to the restrictive "_deny_ has priority over 
 * _allow_" value. If the implementing module needs this setting to be changed 
 * to "_allow_ has priority over _deny_" to function properly, it should warn 
 * admin about this somehow.
 *
 * @param $filepath
 *   The file path, relative to the private download directory.
 *
 * @return
 *   TRUE to allow access, FALSE to deny access, or nothing if the implementing
 *   module is not responsible for the given file.
 */
function hook_private_download_access($filepath) {
  // Never allow access to any file which path contains string 'forbidden'.
  if (strpos($filepath, 'forbidden') !== FALSE) {
    return FALSE;
  }
}

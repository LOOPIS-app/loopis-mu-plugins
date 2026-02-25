<?php
/**
 * Plugin Name: LOOPIS Log
 * Description: Configuring details and filter for debug.log
 * Authors: JH, HH
 * Version: 0.1
 * 
 * Placed in mu-plugins = runs early and cannot be deactivated by accident.
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

/**
 * Filter entries in debug.log for the whole site.
 * 
 * Why filter?
 * Primarily to prevent WPUM plugin from flooding debug.log 
 * 
 * What to keep?
 * - Errors: E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR
 * - Warnings: E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING
 * - Recoverable errors: E_RECOVERABLE_ERROR
 * - Strict standards: E_STRICT
 * - Notices: E_NOTICE
 *
 * What to filter?
 * – Deprecated functions: E_DEPRECATED (a lot used by WPUM)
 * – User notices: E_USER_NOTICE: ("Function _load_textdomain_just_in_time was called incorrectly")
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_NOTICE);


/**
 * Enable detailed logging for LOOPIS components.
 * 
 * Replacing loopis_logger.php in plugin "LOOPIS Config" (using new function names).
 */

// Structured logging for start, success, failure and two tabbed levels
function loopis_log_function_start($function_handle){
    loopis_log_write("Start: function {$function_handle}...");
}

function loopis_log_function_success($function_handle){
    loopis_log_write("End: function {$function_handle} success!");
    error_log("");
}

function loopis_log_function_failure($function_handle){
    loopis_log_write("End: function {$function_handle} failed!");
    error_log("");
}

function loopis_log_level1($message){
 loopis_log_write("     {$message}");
}

function loopis_log_level2($message){
    loopis_log_write("         {$message}");
}

// Fix timestamp offset
function loopis_log_get_timestamp(){
    if (function_exists('wp_date')) {
        return wp_date('Y-m-d H:i:s T');
    }
    return gmdate('Y-m-d H:i:s') . ' UTC';
}

// Final debug.log writing
function loopis_log_write($output){
    error_log('[' . loopis_log_get_timestamp() . '] ' . (string) $output);
}
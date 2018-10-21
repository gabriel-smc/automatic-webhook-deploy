<?php
/**
 * @module log
 * @version 2018.10.21, 00:24
 *
 * Logging module.
 *
 * ---
 * Igor Lilliputten
 * mailto: igor at lilliputten dot ru
 * http://lilliputten.ru/
 *
 * Ivan Pushkin
 * mailto: iv dot pushk at gmail dot com
 */

/*{{{ Global variables */

$_LOG_FILE    = 'log.txt'; // default log file name
$_LOG_ENABLED = false;     // set to 'true' for enabling logging

/*}}}*/

function _LOG_INIT ()/*{{{*/
{
    global $CONFIG, $_LOG_ENABLED, $_LOG_FILE;

    if ( !empty($CONFIG['log']) ) {
        $_LOG_ENABLED = true;
    }

    if ( !empty($CONFIG['logFile']) ) {
        $_LOG_FILE = $CONFIG['logFile'];
    }

    if ( !empty($CONFIG['setTimezone']) ) {
        date_default_timezone_set($CONFIG['setTimezone']);
    }

    if ( !empty($CONFIG['logClear']) ) {
        _LOG_CLEAR();
    }

}/*}}}*/
function _LOG_CLEAR ()/*{{{*/
{
    global $_LOG_FILE;

    if ( !empty($GLOBALS['_LOG_ENABLED']) ) {
        if ( is_file($_LOG_FILE) ) {
            unlink($_LOG_FILE);
        }
    }
}/*}}}*/
function _LOG ($s)/*{{{*/
{
    if ( !empty($GLOBALS['_LOG_ENABLED']) ) {
        $datetime = date('Y.m.d H:i:s');
        file_put_contents($GLOBALS['_LOG_FILE'], $datetime."\t".$s."\n", FILE_APPEND | LOCK_EX);
        flush();
    }
}/*}}}*/
function _LOG_VAR ($s,$p)/*{{{*/
{
    _LOG($s.': '.var_export($p,true));
}/*}}}*/
function _ERROR ($s)/*{{{*/
{
    _LOG('ERROR: '.$s);
}/*}}}*/
function _DEBUG ($s)/*{{{*/
{
    global $CONFIG;
    _LOG('DEBUG: '.$s);
}/*}}}*/
function _DEBUG_VAR ($s,$p)/*{{{*/
{
    _DEBUG($s.': '.var_export($p,true)); // print_r($p,true));
}/*}}}*/


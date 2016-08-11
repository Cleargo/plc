<?php

/**
 * MANAdev logging function
 */
if (!function_exists('_log')) {
    function _log($message, $filename = 'mana.log') {
        $filename = BP . '/var/log/' . $filename;
        $s = file_exists($filename) ? file_get_contents($filename) : '';
        file_put_contents($filename, $s . $message . "\n");
    }
}

if (!function_exists('_logStackTrace')) {
    function _logStackTrace($filename = 'mana.log') {
        try {
            throw new \Exception();
        }
        catch (\Exception $e) {
            _log($e->getTraceAsString(), $filename);
        }
    }
}
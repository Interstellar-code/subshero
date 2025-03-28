<?php

require_once('Helper.php');

if (!function_exists('lib')) {
    function lib()
    {
        if (!isset($GLOBALS['__lib'])) {
            $GLOBALS['__lib'] = new App\Library\Application;
        }
        return $GLOBALS['__lib'];
    };
}

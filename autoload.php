<?php
spl_autoload_register(function($className) {
    $file = str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $className) . '.php';
    if (strpos($className, 'RuleEngine') === 0) {
        //var_dump((new Exception())->getTraceAsString());
        require_once __DIR__ . '/src/' . $file;
    }
});

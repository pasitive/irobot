<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    // application components
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
    ),
    'components' => array(
        'db' => array(
            'connectionString' => '[[database.type]]:host=[[database.console.host]];dbname=[[database.console.basename]]',
            'username' => '[[database.console.username]]',
            'password' => '[[database.console.password]]',
            'charset' => '[[database.charset]]',
            'emulatePrepare' => '[[database.emulatePrepare]]',
            'tablePrefix' => '[[database.tablePrefix]]',
            'schemaCachingDuration' => '[[database.schemaCachingDuration]]',
            'queryCachingDuration' => '[[database.queryCachingDuration]]',
            'enableParamLogging' => '[[database.enableParamLogging]]',
            'enableProfiling' => '[[database.enableProfiling]]',
        ),
    ),
);
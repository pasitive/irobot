<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'iRobot-Управление',

    'preload' => array('log'),

    'language' => 'ru',

    'defaultController' => 'robot',

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
    ),

    'modules' => array(
        'api',
    ),

    // application components
    'components' => array(
        'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            'params' => array('directory' => '/opt/local/bin'),
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager'
        ),
        'user' => array(
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/session/create'),
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(

                'login' => 'session/create',
                'logout' => 'session/delete',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'errorHandler' => array(
            'errorAction' => 'error/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
        'db' => array(
            'connectionString' => '[[database.type]]:host=[[database.host]];dbname=[[database.basename]]',
            'username' => '[[database.username]]',
            'password' => '[[database.password]]',
            'charset' => '[[database.charset]]',
            'emulatePrepare' => '[[database.emulatePrepare]]',
            'tablePrefix' => '[[database.tablePrefix]]',
            'schemaCachingDuration' => '[[database.schemaCachingDuration]]',
            'queryCachingDuration' => '[[database.queryCachingDuration]]',
            'enableParamLogging' => '[[database.enableParamLogging]]',
            'enableProfiling' => '[[database.enableProfiling]]',
        ),
        'cache' => array(
            'class' => 'CDummyCache',
        ),
    ),

    'params' => array(
        'adminEmail' => 'positivejob@yandex.ru',
        'uploadDir' => Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads',
        'secret' => 'c0018634f1e7cb8788e4bec64f469e52',
    ),
);
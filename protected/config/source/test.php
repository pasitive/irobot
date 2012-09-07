<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'operpom',

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
    ),

    'language' => 'ru',

    'components' => array(
        'fixture' => array(
            'class' => 'system.test.CDbFixtureManager',
        ),
        'db' => array(
            'connectionString' => '[[database.type]]:host=[[database.host]];dbname=[[database.basename]]_test',
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
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute', //CProfileLogRoute
                ),
            ),
        ),
    ),
    'modules' => array(
        'user' => array(
            // allow logining without active email
            'loginNotActiv' => true,
            // captcha not show
            'captcha' => array('registration' => false),
            // названия таблиц взяты по умолчанию, их можно изменить
        ),
        'news' => array(),
        'advert' => array(
            'pathToLogos' => '',
        ),
        'sticker' => array(),
    ),
);


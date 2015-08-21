<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Blog Demo',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'defaultController'=>'index',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=EAS',
			'emulatePrepare' => true,
			'username' => 'danis',
			'password' => 'gn0233',
			// 'username' => 'root',
			// 'password' => '87976705',
			'charset' => 'utf8',
			'tablePrefix' => 'ea_',
		),

		//TOS-CORE
		'core'=>array(
		    'class'=>'CDbConnection',
		    'connectionString' => 'mysql:host=127.0.0.1;port=3307;dbname=core',
		    'emulatePrepare' => true,
		    'username' => 'core',
		    'password' => 'z3Ah1pEGznu9',
		    'charset' => 'utf8',
		    'tablePrefix' => '',		
		),

		//TOS-CORE
		'treport'=>array(
		    'class'=>'CDbConnection',
		    'connectionString' => 'mysql:host=127.0.0.1;port=3307;dbname=treport',
		    'emulatePrepare' => true,
		    'username' => 'treport',
		    'password' => 'dUWjXdb4gZQi',
		    'charset' => 'utf8',
		    'tablePrefix' => '',		
		),

		//upm
		'upm'=>array(
		    'class'=>'CDbConnection',
		    'connectionString' => 'mysql:host=127.0.0.1;port=3307;dbname=upm',
		    'emulatePrepare' => true,
		    'username' => 'upm',
		    'password' => 'Z1RqR6i18qFf',
		    'charset' => 'utf8',
		    'tablePrefix' => '',		
		),

		//events
		'eve'=>array(
		    'class'=>'CDbConnection',
		    'connectionString' => 'mysql:host=127.0.0.1;port=3308;dbname=TEST',
		    'emulatePrepare' => true,
		    'username' => 'root',
		    'password' => '87976705',
		    'charset' => 'utf8',
		    'tablePrefix' => 'ytb_',		
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

		'urlManager'=>array(
			'showScriptName'=>false,
			'urlFormat'=>'path',
			'rules'=>array(
				'gii'=>'gii',
				'gii/<controller:\w+>'=>'gii/<controller>',
				'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),	
	),
    'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'IZEA',
            // 'ipFilters'=>array(...a list of IPs...),
            // 'newFileMode'=>0666,
            // 'newDirMode'=>0777,
        ),
    ),
	

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Global Abundance System',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

    'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>/<returnId:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<data:\w+>'=>'<controller>/<action>',
                        ),
		),

		'db'=>array(
			'connectionString' => 'mysql:host=*****;dbname=*****',
			'emulatePrepare' => true,
			'username' => '*****',
			'password' => '*****',
			'charset' => 'utf8',
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'languages' => array(
				'en' => 'English',
				'es' => 'Español',
			),	
		'default language'=>'en',

		'development url'=>'https://github.com/margori/Global-Abundance-System',
		'blog url'=>'http://gasystem.wordpress.com/',
		'contact email'=>'gasdemo@yahoo.com',

		'notify emails'=>'yes', // yes or no
		'smtp_server' => '*****',
		'smtp_port' => 25,
		'smtp_username' => '*****',
		'smtp_password' => '*****',
		'smtp_from_email' => '*****',
		'smtp_from_name' => 'Global Abundance System',
		'smtp_secure' => 'ssl',  // '','ssl','tls'
		'smtp_timeout' => 20,  // seconds
	),
);
?>
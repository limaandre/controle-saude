<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

include('main-db.php');
include('main-email.php');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Minha Saúde',
   /* 'theme' => 'beoro',*/
    // preloading 'log' component
    'preload' => array('log'),
    'language' => 'pt_br',
    'sourceLanguage' => '00',
    'charset' => 'ISO-8859-1',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
		'application.models.SignUp',
        'application.components.*',
        'ext.yii-mail.YiiMailMessage',
        'application.helpers.*',
        'ext.behaviors.AttachmentBehavior',
        'ext.giix-components.*',
        'ext.yii-phpmailer.YiiMailer',
		'ext.CJuiDateTimePicker.CJuiDateTimePicker',
        'ext.google.*', //Google Charts
		'ext.galleryManager.models.*',
		'ext.galleryManager.*',
		'ext.validator.cpf',
        'ext.validator.cnpj',
    ),
    'aliases' => array(
        //If you manually installed it
        'xupload' => 'ext.xupload',
    ),
    'controllerMap' => array(
        // 'gallery' => array(
        //     'class' => 'ext.galleryManager.GalleryController',
        // ),
	),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        
		'gii' => array(
            'class' => 'ext.gii.GiiModule',
            'password' => 'saude123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1','::1', '192.168.0.*', 'dev', '10.0.2.2'),
            'generatorPaths' => array(
                'ext.giix-core', // giix generators
            ),
           
        ),
		
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
        // ImageMagick setup path
        ),
        'mail' => array(
            'class' => 'application.extensions.yii-mail.YiiMail',
            'transportType' => 'php', /// case sensitive!
            'transportOptions' => array(
                'host' => $email['host'],
                //'username'=>$email['username'],
                // or email@googleappsdomain.com
                //'password'=>$email['password'],
                'port' => $email['port'],
            //'encryption'=>'ssl',
            ),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
        'metadata' => array('class' => 'Metadata'),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(				
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
			'class'=>'CDbConnection',
            'connectionString' => 'mysql:host=' . $db['host'] . ';dbname=' . $db['db'].';port=3306',
            'username' => $db['username'],
            'password' => $db['password'],
            'charset' => 'latin1',
			'persistent' => true,
            'enableProfiling' => true,
            'enableParamLogging' => true,
			'emulatePrepare' => true,
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'defaultPageSize' => 10,
    ),
);

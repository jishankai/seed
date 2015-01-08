<?php

require_once(dirname(__FILE__) . '/seed/const.cfg.php');
require_once(dirname(__FILE__) . '/seed/app.cfg.php');
Yii::setPathOfAlias('pogostick', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'pogostick');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    'language' => 'ja',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.achievement.*',
        'application.models.mission.*',
        'application.components.*',
        'application.extensions.*',
        'application.extensions.ApnStupid.*',
        'pogostick.base.*',
        'pogostick.behaviors.*',
        'pogostick.components.*',
        'pogostick.events.*',
        'pogostick.helpers.*',
        'pogostick.widgets.*',
    ),
    'modules' => array(
        'admin',
        'famitsuCode' => array(
            'dbID' => 'db',
            'beginTime' => '2012-08-01 00:00:00',
            'endTime' => '2012-10-31 23:59:59',
            'backRoute' => 'event/index',
            'returnRoute' => 'event/index',
            'maxCodesPerUser' => 100000,
            'userClass' => 'SeedFamitsuUser',
            'layout' => 'main',
            'layoutPath' => 'protected/modules/famitsuCode/views/layouts/',
        ),
        'apurifanCode' => array(
            'dbID' => 'db',
            'beginTime' => '2012-09-22 00:00:00',
            'endTime' => '2112-10-31 23:59:59',
            'backRoute' => 'event/index',
            'returnRoute' => 'event/index',
            'maxCodesPerUser' => 100000,
            'userClass' => 'SeedApurifanUser',
            'layout' => 'main',
            'layoutPath' => 'protected/modules/apurifanCode/views/layouts/',
        ),
    // uncomment the following to enable the Gii tool
    /*
      'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'Enter Your Password Here',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      ),
     */
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        'cache' => array(
            'class' => 'CMemCache',
            'keyPrefix' => 'SeedData',
            'servers' => array(
                array(
                    'host' => '192.168.1.93',
                    'port' => 11211,
                    'weight' => 100,
                ),
            ),
        ),
        'sessionCache' => array(
            'class' => 'CMemCache',
            'keyPrefix' => 'SeedSession',
            'servers' => array(
                array(
                    'host' => '192.168.1.93',
                    'port' => 11211,
                    'weight' => 100,
                ),
            ),
        ),
        'session' => array(
            'class' => 'CCacheHttpSession',
            'cacheID' => 'sessionCache',
            'sessionName' => 'SID',
            'cookieMode' => 'none',
            'timeout' => 86400,
        ),
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=192.168.1.93;dbname=seed2',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'schemaCachingDuration' => 86400,
        ),
        // uncomment the following to use a MySQL database

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'objectLoader' => array(
            'class' => 'SimpleObjectLoader',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
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
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'testMode' => false,
    	'size'		=> 0.5,
        //  Twitter API
        'twitter' => array(
            'consumer_key' => 'JhLH3P8Y9mUy4jqMv9VgpA',
            'consumer_secret' => 'rgqxfxgTeFkAoyLGpVg1D9IXhGgEUQ9pS1fbKCggw',
            'extFile1' => '/../extensions/share/tmhOAuth.php',
            'extFile2' => '/../extensions/share/tmhUtilities.php',
            'uploadPath' => '/../../photoUpload/',
        ),
        //  Facebook Connect
        'facebook' => array(
            'appId' => '133252023487139',
            'secret' => '8f1edabc544246f7ba6787f3688a95d2',
            'extFile' => '/../extensions/share/facebook.php',
        ),
        'uploadPath' => '/../../photoUpload/',
    ),
);

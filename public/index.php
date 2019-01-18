<?php

// Composer autoloader
require __DIR__.'/../vendor/autoload.php';

use OCFram\Application;
use OCFram\Config\ApplicationConfig;
use OCFram\Database\DBAL\DatabaseAccessObject;
use OCFram\Database\DBAL\Driver\DBDriverFactory;
use OCFram\Entities\EntityManager;
use OCFram\HTTPComponents\HTTPRequest;
use OCFram\HTTPComponents\HTTPResponse;
use OCFram\Routing\RouteParser\RouteParserXML;
use OCFram\Routing\Router;
use OCFram\User\BaseUser;

// Constants
define('APPLICATION_ROOT_PATH', __DIR__.'/../');
define('APPLICATION_VIEW_FILES_PATH', APPLICATION_ROOT_PATH.'templates/');
define('APPLICATION_CONFIG_FILE_PATH', __DIR__.'/../config/config.xml');

// Initialize application components
$applicationConfig = new ApplicationConfig(APPLICATION_CONFIG_FILE_PATH);
$httpRequest = new HTTPRequest();
$httpResponse = new HTTPResponse();
$routeParser = new RouteParserXML($applicationConfig->getConfigVariable('routesFilePath'));
$router = new Router();
$router->setRoutes($routeParser->getRoutesFromRouteFile());
$user = new BaseUser();

// Template engine (Twig here)
// Possible Optimization : Use Twig Template Caching
$twigLoader = new Twig_Loader_Filesystem(APPLICATION_VIEW_FILES_PATH);
$templateEngine = new Twig_Environment($twigLoader);

// Initialize Database Connection and Entity Manager
// Only PDOMysql is available by now
$dbDriverName = $applicationConfig->getConfigVariable('db_driver');
$dbHost = $applicationConfig->getConfigVariable('db_host');
$dbName = $applicationConfig->getConfigVariable('db_name');
$dbUser = $applicationConfig->getConfigVariable('db_user');
$dbPassword = $applicationConfig->getConfigVariable('db_password');

$dbDriver = DBDriverFactory::createDBDriver($dbDriverName, $dbHost, $dbName, $dbUser, $dbPassword, null);
$databaseAccessObject = new DatabaseAccessObject($dbDriver);
$databaseAccessObject->initDbConnection();

$entityManager = new EntityManager($databaseAccessObject);

// Initialize application
$application = new Application($applicationConfig, $router, $httpRequest, $httpResponse, $entityManager, $templateEngine, $user);
$application->run();

// Initialize application components (components that must be aware of the application)

echo 'it works';


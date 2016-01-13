<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require 'autoload.php';
$loader->addPsr4('Application\\', __DIR__ . '/src/Application');
$loader->register();

$appParameters = require 'config/parameters.php';
$isDevMode = false;

# Doctrine ORM setup
$config = Setup::createConfiguration($isDevMode);
$driver = new AnnotationDriver(new AnnotationReader(), __DIR__ . '/src/Application/Entity');
// registering noop annotation autoloader - allow all annotations by default
//AnnotationRegistry::registerLoader('class_exists');
$config->setMetadataDriverImpl($driver);
$config->setEntityNamespaces(['Application\Entity']);
$entityManager = EntityManager::create($appParameters['db'], $config);

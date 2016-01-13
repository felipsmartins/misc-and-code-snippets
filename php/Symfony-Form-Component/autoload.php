<?php

use Doctrine\Common\Annotations\AnnotationRegistry;


/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require 'vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;

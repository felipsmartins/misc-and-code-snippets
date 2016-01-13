<?php

use Application\Entity;
use Application\Form\Type\ProductType;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\Form\Forms;

require 'bootstrap.php';

$repo = $entityManager->getRepository(Entity\Product::class);

//$managerRegistry = new \Application\Registry([$entityManager->getConnection()], [$entityManager], 'default', 'default', 'default');

$doctrineOrmExtension = new DoctrineOrmExtension($managerRegistry);
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension($doctrineOrmExtension)->getFormFactory();
$formFactory->create(ProductType::class);







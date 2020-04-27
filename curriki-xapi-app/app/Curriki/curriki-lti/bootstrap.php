<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Lib\TablePrefix;
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    Doctrine\ORM\EntityManager::class => DI\factory(function () {
        $paths = array(__DIR__."/src/Core/Entities");
        $isDevMode = true;
        $dbParams = array(
            'driver'   => WP_LTI_DB_DRIVER,
            'host'     => WP_LTI_DB_HOST,
            'user'     => WP_LTI_DB_USER,
            'password' => WP_LTI_DB_PASSWORD,
            'dbname'   => WP_LTI_DB_NAME,
        );
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $evm = new \Doctrine\Common\EventManager;
        $tablePrefix = new TablePrefix(WP_LTI_DB_TABLE_PREFIX);
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);
        $entityManager = EntityManager::create($dbParams, $config, $evm);
        return $entityManager;
    })        
]);
$curriki_lti_instance = $containerBuilder->build();
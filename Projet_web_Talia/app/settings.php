<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

define('APP_ROOT', __DIR__);

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, 
                'logError'            => true,
                'logErrorDetails'     => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                
          
                'doctrine' => [
                    // Passe à false en production
                    'dev_mode' => true,
                    
            // Path where Doctrine will cache the processed metadata
            // when 'dev_mode' is false.
            'cache_dir' => APP_ROOT . '/var/doctrine',

            // List of paths where Doctrine will search for metadata.
            // Metadata can be either YML/XML files or PHP classes annotated
            // with comments or PHP8 attributes.
            'metadata_dirs' => [APP_ROOT . '/src/Domain'],
                    
                    // Configuration de ta base de données
                    'connection' => [
                        'driver'   => 'pdo_mysql',
                        'host'     => 'localhost',
                        'port'     => 3306,
                        'dbname'   => 'toto',
                        'user'     => 'root',
                        'password' => 'example',
                        'charset'  => 'utf8mb4'
                    ]
                ]
                // --- FIN DE DOCTRINE ---
            ]);
        }
    ]);
};
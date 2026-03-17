<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // On définit la racine de l'app pour les chemins Doctrine
    $appRoot = __DIR__ . '/..';

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () use ($appRoot) {
            return new Settings([
                'displayErrorDetails' => true, 
                'logError'            => true,
                'logErrorDetails'     => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                
                // --- AJOUT DE DOCTRINE ICI ---
                'doctrine' => [
                    // Passe à false en production
                    'dev_mode' => true,
                    
                    // Chemin vers le cache (assure-toi que le dossier var/ existe)
                    'cache_dir' => $appRoot . '/var/doctrine',
                    
                    // Chemin vers tes Entités (tes classes PHP Doctrine)
                    'metadata_dirs' => [$appRoot . '/src/Domain'],
                    
                    // Configuration de ta base de données
                    'connection' => [
                        'driver'   => 'pdo_mysql',
                        'host'     => 'localhost',
                        'port'     => 3306,
                        'dbname'   => 'ton_nom_de_bdd',
                        'user'     => 'root',
                        'password' => '',
                        'charset'  => 'utf8' // Attention : 'utf8', pas 'utf-8' pour MySQL
                    ]
                ]
                // --- FIN DE DOCTRINE ---
            ]);
        }
    ]);
};
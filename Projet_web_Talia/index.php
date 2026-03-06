<?php

require_once __DIR__ . '/vendor/autoload.php';

// On dit à Twig où sont les templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');

// On crée l'environnement Twig
$twig = new \Twig\Environment($loader, [
    'debug' => true,
]);

// On affiche le template Accueil
echo $twig->render('Accueil.html.twig');
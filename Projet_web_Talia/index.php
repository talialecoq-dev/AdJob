<?php

require_once __DIR__ . '/vendor/autoload.php';


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');


$twig = new \Twig\Environment($loader, [
    'debug' => true,
]);


echo $twig->render('Accueil.html.twig');
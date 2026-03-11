<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Controller\HomeController;
use App\Application\Controller\EntrepriseController;
use App\Application\Controller\ProfilController;
use App\Application\Controller\CandidaturesController;
use App\Application\Controller\ÉtudiantController;
use App\Application\Controller\PiloteController;
use App\Application\Controller\WishlistController;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', [HomeController::class, 'home']);

    $app->get('/Profil', [ProfilController::class, 'profil']);
    
    $app->get('/Inscription-Entreprise', [EntrepriseController::class, 'inscription']);
    $app->get('/Modifier-Entreprise', [EntrepriseController::class, 'modifier']);
    $app->get('/Supprimer-Entreprise', [EntrepriseController::class, 'supprimer']);

    $app->get('/Inscription-Étudiant', [ÉtudiantController::class, 'inscription']);
    $app->get('/Modifier-Étudiant', [ÉtudiantController::class, 'modifier']);
    $app->get('/Supprimer-Étudiant', [ÉtudiantController::class, 'supprimer']);

    $app->get('/Inscription-Pilote', [PiloteController::class, 'inscription']);
    $app->get('/Modifier-Pilote', [PiloteController::class, 'modifier']);
    $app->get('/Supprimer-Pilote', [PiloteController::class, 'supprimer']);

    $app->get('/Whishlist', [WishlistController::class, 'wishlist']);

    $app->get('/Candidatures', [CandidaturesController::class, 'candidatures']);

    /*
    $app->group('/users', function (Group $group) {Q

        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    */
};

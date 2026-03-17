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
use App\Application\Controller\LoginController;
use App\Application\Controller\Mentions_LegalesController;
use App\Application\Controller\OffresController;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', [HomeController::class, 'home']);

    $app->get('/Mentions_Legales', [Mentions_LegalesController::class, 'Mentions_Legales']);

    $app->get('/Profil', [ProfilController::class, 'profil']);

    $app->get('/Login', [LoginController::class, 'login']);
    $app->post('/Login-utilisateur', [EntrepriseController::class, 'traiteConnexion']);
    
    $app->get('/Inscription-Entreprise', [EntrepriseController::class, 'inscription']);
    $app->get('/Liste-Entreprises', [EntrepriseController::class, 'liste']);
    $app->get('/Supprimer-Entreprise', [EntrepriseController::class, 'supprimer']);
    $app->get('/Rechercher-Entreprise', [EntrepriseController::class, 'recherche_entreprise']);
    



    $app->post('/inscription-entreprise', [EntrepriseController::class, 'traiterInscription']);
    $app->post('/traitement-inscription', [EntrepriseController::class, 'traiterInscription']);



    $app->get('/Inscription-Étudiant', [ÉtudiantController::class, 'inscription']);
    $app->get('/Liste-Étudiants', [ÉtudiantController::class, 'liste']);
    $app->get('/Supprimer-Étudiant', [ÉtudiantController::class, 'supprimer']);
    $app->get('/Modifier-Étudiant', [ÉtudiantController::class, 'modifier']);

    $app->get('/Inscription-Pilote', [PiloteController::class, 'inscription']);
    $app->get('/Liste-Pilotes', [PiloteController::class, 'liste']);
    $app->get('/Supprimer-Pilote', [PiloteController::class, 'supprimer']);
    $app->get('/Modifier-Pilote', [PiloteController::class, 'modifier']);

    $app->get('/Whishlist', [WishlistController::class, 'wishlist']);

    $app->get('/Candidatures', [CandidaturesController::class, 'candidatures']);
    $app->get('/Candidater', [CandidaturesController::class, 'candidater']);

    
    $app->get('/Ajouter-Offre', [OffresController::class, 'ajouter']);
    $app->get('/Offres', [OffresController::class, 'liste']);
    

    /*
    $app->group('/users', function (Group $group) {Q

        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    */
};

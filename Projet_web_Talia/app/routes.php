<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Application\Controller\HomeController;
use App\Application\Controller\EntrepriseController;
use App\Application\Controller\ProfilController;
use App\Application\Controller\CandidaturesController;
use App\Application\Controller\UserController;
use App\Application\Controller\WishlistController;
use App\Application\Controller\LoginController;
use App\Application\Controller\Mentions_LegalesController;
use App\Application\Controller\OffresController;
use App\Application\Middleware\LoggedMiddleware;
use App\Application\Middleware\RoleCheckMiddleware;
use App\Domain\Role;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $factory = $app->getContainer()->get(ResponseFactoryInterface::class);
    
    // --- Accueil ---
    $app->get('/', [HomeController::class, 'home']);
    $app->post('/offre/ajouter', [HomeController::class, 'ajouter']);

    $app->post('/offre/supprimer/{id}', [HomeController::class, 'supprimer']);

    // --- Pages statiques ---
    $app->get('/Mentions_Legales', [Mentions_LegalesController::class, 'Mentions_Legales']);
    $app->get('/Profil', [ProfilController::class, 'profil'])->add(new LoggedMiddleware($factory));

    // --- Login ---
    $app->get('/Login', [LoginController::class, 'login']);
    $app->post('/Login', [LoginController::class, 'login']);

    // --- Entreprises ---
    $app->get('/Inscription-Entreprise', [EntrepriseController::class, 'inscription']);
    $app->post('/inscription-entreprise', [EntrepriseController::class, 'traiterInscription']);
    $app->post('/traitement-inscription', [EntrepriseController::class, 'traiterInscription']);
    $app->get('/Liste-Entreprises', [EntrepriseController::class, 'liste']);
    $app->get('/Rechercher-Entreprise', [EntrepriseController::class, 'recherche_entreprise']);
    $app->get('/Modifier-Entreprise/{id}', [EntrepriseController::class, 'modifier']);
    $app->post('/Update-Entreprise/{id}', [EntrepriseController::class, 'update']);
    $app->get('/Supprimer-Entreprise/{id}', [EntrepriseController::class, 'supprimer']);
    $app->post('/Supprimer-Entreprise/{id}', [EntrepriseController::class, 'supprimer']);

    // --- Étudiants (via UserController) ---
    $app->group('/etudiant', function (RouteCollectorProxy $group) use ($factory) {

        $group->get('/inscription', [UserController::class, 'inscriptionEtudiant'])->setName('inscription_etudiants');
        $group->post('/inscription', [UserController::class, 'ajouterEtudiant']);

        $group->get('/liste', [UserController::class, 'listeEtudiants'])->setName('liste_etudiants');
        
        $group->get('/modifier/{id}', [UserController::class, 'modifierEtudiant']);
        $group->post('/modifier/{id}', [UserController::class, 'updateEtudiant']);

        $group->post('/supprimer/{id}', [UserController::class, 'supprimerEtudiant']);
        
    })->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]));


    // --- Pilotes (via UserController) ---
    $app->get('/Inscription-Pilote', [UserController::class, 'inscriptionPilote']);
    $app->post('/Inscription-Pilote', [UserController::class, 'ajouterPilote']);
    $app->get('/Liste-Pilotes', [UserController::class, 'listePilotes']);
    $app->get('/Modifier-Pilote/{id}', [UserController::class, 'modifierPilote']);
    $app->post('/Update-Pilote/{id}', [UserController::class, 'updatePilote']);
    $app->get('/Supprimer-Pilote/{id}', [UserController::class, 'supprimerPilote']);
    $app->post('/Supprimer-Pilote/{id}', [UserController::class, 'supprimerPilote']);

    // --- Offres ---
    $app->get('/Ajouter-Offre', [OffresController::class, 'ajouter']);
    $app->post('/Ajouter-Offre', [OffresController::class, 'ajouter']);
    $app->get('/Offres', [OffresController::class, 'liste']);

    // --- Wishlist ---
    $app->get('/Whishlist', [WishlistController::class, 'wishlist']);
    $app->post('/wishlist/ajouter/{id}', [WishlistController::class, 'ajouter']);
    $app->post('/wishlist/retirer/{id}', [WishlistController::class, 'retirer']);

    // --- Candidatures ---
    $app->get('/Candidatures', [CandidaturesController::class, 'candidatures']);
    $app->post('/Candidatures', [CandidaturesController::class, 'candidatures']);
    $app->get('/Candidater', [CandidaturesController::class, 'candidater']);
    $app->post('/Candidatures/ajouter', [CandidaturesController::class, 'ajouter']);
    $app->post('/Supprimer-Candidature/{id}', [CandidaturesController::class, 'supprimer']);
};
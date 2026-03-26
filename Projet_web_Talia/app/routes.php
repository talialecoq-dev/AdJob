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
        return $response;
    });

    // --- Accueil / Offres ---
    $app->get('/', [HomeController::class, 'home']);
    $app->post('/offre/ajouter', [HomeController::class, 'ajouter']);
    $app->post('/offre/supprimer/{id}', [HomeController::class, 'supprimer']);

    // --- Pages statiques ---
    $app->get('/Mentions_Legales', [Mentions_LegalesController::class, 'Mentions_Legales']);
    $app->get('/Profil', [ProfilController::class, 'profil']);

    // --- Login ---
    $app->get('/Login', [LoginController::class, 'login']);
    // $app->post('/Login-utilisateur', [LoginController::class, 'traiteConnexion']);

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

    // --- Étudiants ---
    $app->get('/Inscription-Étudiant', [ÉtudiantController::class, 'inscription']);
    $app->post('/Inscription-Étudiant', [ÉtudiantController::class, 'ajouter']);
    $app->get('/Liste-Étudiants', [ÉtudiantController::class, 'liste']);
    $app->get('/Modifier-Étudiant/{id}', [ÉtudiantController::class, 'modifier']);
    $app->post('/Update-Étudiant/{id}', [ÉtudiantController::class, 'update']);
    $app->post('/Supprimer-Étudiant/{id}', [ÉtudiantController::class, 'supprimer']);

    // --- Pilotes ---
    $app->get('/Inscription-Pilote', [PiloteController::class, 'inscription']);
    $app->post('/Inscription-Pilote', [PiloteController::class, 'ajouter']);
    $app->get('/Liste-Pilotes', [PiloteController::class, 'liste']);
    $app->get('/Modifier-Pilote/{id}', [PiloteController::class, 'modifier']);
    $app->post('/Update-Pilote/{id}', [PiloteController::class, 'update']);
    $app->get('/Supprimer-Pilote/{id}', [PiloteController::class, 'supprimer']);
    $app->post('/Supprimer-Pilote/{id}', [PiloteController::class, 'supprimer']);

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
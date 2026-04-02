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
        $app->get('/Logout', [LoginController::class, 'logout']);
        $app->post('/Logout', [LoginController::class, 'logout']);


        // --- Entreprises ---
        $app->group('/entreprise', function (RouteCollectorProxy $group) use ($factory) {
        $group->get('/Inscription-Entreprise', [EntrepriseController::class, 'inscription'])->setName('inscription_entreprises');

        $group->post('/inscription-entreprise', [EntrepriseController::class, 'traiterInscription']);
        $group->post('/traitement-inscription', [EntrepriseController::class, 'traiterInscription']);

        $group->get('/Liste-Entreprises', [EntrepriseController::class, 'liste'])->setName('liste_entreprises');

        $group->get('/Rechercher-Entreprise', [EntrepriseController::class, 'recherche_entreprise'])->setName('recherche_entreprise');

        $group->get('/Modifier-Entreprise/{id}', [EntrepriseController::class, 'modifier'])->setName('modifier_entreprises');
        $group->post('/Update-Entreprise/{id}', [EntrepriseController::class, 'update']);

        $group->get('/Supprimer-Entreprise/{id}', [EntrepriseController::class, 'supprimer']);
        $group->post('/Supprimer-Entreprise/{id}', [EntrepriseController::class, 'supprimer']);

        })->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]));

    // --- GESTION DES UTILISATEURS ---

        $app->get('/Liste-Utilisateurs', [UserController::class, 'listeUsers'])
        ->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]))
        ->setName('liste_utilisateurs');

        $app->get('/users/inscription-etudiant', [UserController::class, 'inscriptionEtudiant'])
        ->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]))
        ->setName('inscription_etudiants');

        $app->post('/users/inscription-etudiant', [UserController::class, 'ajouterEtudiant'])
        ->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]));

        $app->get('/users/inscription-pilote', [UserController::class, 'inscriptionPilote'])
        ->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]))
        ->setName('inscription_pilotes');

        $app->post('/users/inscription-pilote', [UserController::class, 'ajouterPilote'])
        ->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]));


        $app->group('/users', function (RouteCollectorProxy $group) {

            // Modifications & Suppressions (Étudiants)
            $group->get('/etudiant/modifier/{id}', [UserController::class, 'modifierEtudiant'])->setName('modifier_etudiant');
            $group->post('/etudiant/modifier/{id}', [UserController::class, 'updateEtudiant']);
            $group->post('/etudiant/supprimer/{id}', [UserController::class, 'supprimerEtudiant']);

            // Modifications & Suppressions (Pilotes)
            $group->get('/pilote/modifier/{id}', [UserController::class, 'modifierPilote'])->setName('modifier_pilote');
            $group->post('/pilote/modifier/{id}', [UserController::class, 'updatePilote']);
            $group->post('/pilote/supprimer/{id}', [UserController::class, 'supprimerPilote']);

        })->add(new RoleCheckMiddleware($factory, [Role::PILOTE, Role::ADMIN]));

        // --- Offres ---

        $app->get('/Ajouter-Offre', [OffresController::class, 'ajouter']);
        $app->post('/Ajouter-Offre', [OffresController::class, 'ajouter']);

        $app->get('/InfosOffres', [OffresController::class, 'infosOffres'])->setName('infos_offres');

        $app->get('/Offres', [OffresController::class, 'liste']);


        // --- Wishlist ---
        $app->group('/wishlist', function (RouteCollectorProxy $group) use ($factory) {
        $group->get('/', [WishlistController::class, 'wishlist'])->setName('consulter_wishlist');

        $group->post('/ajouter/{id}', [WishlistController::class, 'ajouter'])->setName('ajouter_wishlist');

        $group->post('/retirer/{id}', [WishlistController::class, 'retirer'])->setName('retirer_wishlist');
        }) ->add(new RoleCheckMiddleware($factory, [Role::ETUDIANT, Role::PILOTE, Role::ADMIN]));
        // --- Candidatures ---

        $app->get('/Candidatures', [CandidaturesController::class, 'candidatures']);
        $app->post('/Candidatures', [CandidaturesController::class, 'candidatures']);
        $app->get('/Candidater', [CandidaturesController::class, 'candidater']);
        $app->post('/Candidatures/ajouter', [CandidaturesController::class, 'ajouter']);
        $app->post('/Supprimer-Candidature/{id}', [CandidaturesController::class, 'supprimer']);
    };
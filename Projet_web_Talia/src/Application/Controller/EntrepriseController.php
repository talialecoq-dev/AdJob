<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class EntrepriseController
{
    // Affiche le formulaire d'inscription
    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        // On passe bien 'type' => 'Entreprise' pour que le Twig sache quoi afficher
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type' => 'Entreprise'
        ]);
    }

    // Affiche la liste des entreprises
    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Liste_Entreprises.html.twig', []);
    }

    // Affiche la page de suppression
    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Supprimer_Entreprise.html.twig', []);
    }

    // Traite les données envoyées par le formulaire (POST)
    public function traiterInscription(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $donneesFormulaire = $request->getParsedBody();

        $nom      = $donneesFormulaire['nom_entreprise'] ?? '';
        $tel      = $donneesFormulaire['telephone'] ?? '';
        $secteur  = $donneesFormulaire['secteur'] ?? '';
        $siteWeb  = $donneesFormulaire['site_web'] ?? '';
        $email    = $donneesFormulaire['email'] ?? '';

        try {
            // Ici tu inséreras ton code SQL plus tard
            error_log("Entreprise enregistrée : " . $nom);
        } catch (\Exception $e) {
            error_log("Erreur : " . $e->getMessage());
        }

        // Redirection vers la liste
        return $response
            ->withHeader('Location', '/Liste-Entreprises')
            ->withStatus(302);
    }
} // <--- C'est cette accolade qui doit fermer la classe à la toute fin !
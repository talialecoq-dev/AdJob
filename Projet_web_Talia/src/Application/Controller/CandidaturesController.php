<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class CandidaturesController
{
    public function candidatures(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $candidatures = $_SESSION['candidatures'] ?? [];

        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Candidatures.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    public function ajouter(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = $request->getParsedBody();

        // Récupérer les compétences en tableau
        $competences = $data['competences'] ?? [];

        // Créer l'ID unique pour chaque candidature
        $index = count($_SESSION['candidatures'] ?? []);

          $nouvelleCandidature = [
            'nom'         => trim($data['nom'] ?? ''),
            'prenom'      => trim($data['prenom'] ?? ''),
            'email'       => trim($data['email'] ?? ''),
            'titre'       => trim($data['titre'] ?? 'Offre inconnue'),
            'remuneration'=> trim($data['remuneration'] ?? ''),
            'duree'       => trim($data['duree'] ?? ''),
            'domaine'     => trim($data['domaine'] ?? ''),
            'entreprise'  => trim($data['entreprise'] ?? ''),
            'logo'        => trim($data['logo'] ?? ''),
            'competences' => array_map('trim', $data['competences'] ?? []),
            'description' => trim($data['description'] ?? ''),

            'statut'      => 'En attente',
            'color'       => 'warning',
            'image'       => 'Image/Martin.png',
            'desc'        => 'Candidature soumise par ' . trim($data['nom'] ?? '') . ' pour l\'offre ' . trim($data['titre'] ?? ''),
        ];

        $_SESSION['candidatures'][$index] = $nouvelleCandidature;

        return $response
            ->withHeader('Location', '/Candidatures')
            ->withStatus(302);
    }

    public function supprimer(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = (int) $args['id'];

        if (isset($_SESSION['candidatures'][$id])) {
            unset($_SESSION['candidatures'][$id]);
            $_SESSION['candidatures'] = array_values($_SESSION['candidatures']); // réindexer
        }

        return $response
            ->withHeader('Location', '/Candidatures')
            ->withStatus(302);
    }

    public function candidater(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Page_Modal_Candidature.html.twig', []);
    }
}
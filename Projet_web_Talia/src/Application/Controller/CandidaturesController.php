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

        
        $nouvelleCandidature = [
            'nom'    => trim($data['nom'] ?? ''),
            'prenom' => trim($data['prenom'] ?? ''),
            'email'  => trim($data['email'] ?? ''),
            'titre'  => trim($data['offre_titre'] ?? 'Offre inconnue'),
            'statut' => 'En attente',
            'color'  => 'warning',       
            'image'  => 'Image/Martin.png',
            'desc'   => 'Candidature soumise par ' . trim($data['prenom'] ?? '') . ' ' . trim($data['nom'] ?? ''),
        ];

        
        $_SESSION['candidatures'][] = $nouvelleCandidature;

        
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
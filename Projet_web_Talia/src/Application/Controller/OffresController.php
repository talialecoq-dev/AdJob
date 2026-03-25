<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class OffresController
{
    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($request->getMethod() === 'POST') {

            $data = $request->getParsedBody();
            $id = count($_SESSION['offres'] ?? []);

            $nouvelleOffre = [
                'id'          => $id,
                'titre'       => trim($data['titre'] ?? ''),
                'remuneration'=> trim($data['remuneration'] ?? ''),
                'duree'       => trim($data['duree'] ?? ''),
                'domaine'     => trim($data['domaine'] ?? ''),
                'entreprise'  => trim($data['entreprise'] ?? ''),
                'logo'        => trim($data['logo'] ?? ''),
                'competences' => array_map('trim', $data['competences'] ?? []),
                'description' => trim($data['description'] ?? '')
            ];

        
            $_SESSION['offres'][$id] = $nouvelleOffre;

            return $response
                ->withHeader('Location', '/offres')
                ->withStatus(302);
        }

        $view = Twig::fromRequest($request);
        return $view->render($response, 'Offres/Page_Modal_Ajout_Offre.html.twig', []);
    }
}
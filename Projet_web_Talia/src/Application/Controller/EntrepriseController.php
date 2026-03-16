<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class EntrepriseController
{
    
    
    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type' => 'Entreprise'
        ]);
    }

    
    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Liste_Entreprises.html.twig', []);
    }

    
    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Supprimer_Entreprise.html.twig', []);
    }

    
    public function traiterInscription(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $donneesFormulaire = $request->getParsedBody();

        $nom      = $donneesFormulaire['nom_entreprise'] ?? '';
        $tel      = $donneesFormulaire['telephone'] ?? '';
        $secteur  = $donneesFormulaire['secteur'] ?? '';
        $siteWeb  = $donneesFormulaire['site_web'] ?? '';
        $email    = $donneesFormulaire['email'] ?? '';

        try {
            
            error_log("Entreprise enregistrée : " . $nom);
        } catch (\Exception $e) {
            error_log("Erreur : " . $e->getMessage());
        }
foreach($_FILES as $file){

    if($file['error'] === 0){

        $allowedExtensions = ['png','jpg','jpeg'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($extension, $allowedExtensions)){
            echo "Erreur : seuls les fichiers PNG, JPG ou JPEG sont autorisés.";
            exit;
        }

    }

    }
        
        return $response
            ->withHeader('Location', '/Liste-Entreprises')
            ->withStatus(302);
    }



    public function recherche_entreprise(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
        {
            $view = Twig::fromRequest($request);

            $entreprises = [
                [
                    "id" => 1,
                    "nom" => "LIMAGRIN",
                    "image" => "Images/Image_Limagrin.png",
                    "secteur" => "Agriculture",
                    "type" => "Semancier",
                    "description" => "LIMAGRIN est une coopérative semencière française spécialisée dans la sélection et la production de semences de grandes cultures.",
                    "ville" => "Toulouse",
                    "site_web" => "https://www.limagrain.com"
                ],
                [
                    "id" => 2,
                    "nom" => "TechCorp",
                    "image" => "Images/Image_TechCorp.png",
                    "secteur" => "Informatique",
                    "type" => "ESN",
                    "description" => "TechCorp est une entreprise de services numériques spécialisée dans le développement web et mobile pour grands comptes.",
                    "ville" => "Paris",
                    "site_web" => "https://techcorp.com"
                ],
                [
                    "id" => 3,
                    "nom" => "NetSolutions",
                    "image" => "Images/Image_NetSolutions.png",
                    "secteur" => "Réseaux",
                    "type" => "Intégrateur",
                    "description" => "NetSolutions conçoit et maintient des infrastructures réseau complexes pour des entreprises en environnement cloud hybride.",
                    "ville" => "Lyon",
                    "site_web" => "https://www.netsolutions.com"

                ]
            ];

            return $view->render($response, 'Entreprises/Page_Consulter_Entreprises.html.twig', [
                'entreprises' => $entreprises
            ]);
        }
} 
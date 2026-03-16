<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class EntrepriseController
{
    public function recherche_entreprise(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Consulter_Entreprises.html.twig', []);
    }
    
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
} 
<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ÉtudiantController
{
    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Étudiants/Page_Inscription_Étudiant.html.twig', []);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $etudiants = [
            [
                "id" => 1,
                "prenom" => "Mark",
                "nom" => "Otto",
                "email" => "mark@cesi.fr",
                "campus" => "Cesi École d'Ingénieurs",
                "ville" => "Paris"
            ],
            [
                "id" => 2,
                "prenom" => "Jacob",
                "nom" => "Thornton",
                "email" => "jacob@cesi.fr",
                "campus" => "Cesi École d'Ingénieurs",
                "ville" => "Lyon"
            ],
            [
                "id" => 3,
                "prenom" => "John",
                "nom" => "Doe",
                "email" => "john@cesi.fr",
                "campus" => "Cesi École d'Ingénieurs",
                "ville" => "Marseille"
            ]
        ];

        // Envoi à Twig
        return $view->render($response, 'Étudiants/Page_Liste_Étudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
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
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Étudiants/Page_Supprimer_Étudiant.html.twig', []);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Étudiants/Page_Modifier_Étudiant.html.twig', []);
    }
}
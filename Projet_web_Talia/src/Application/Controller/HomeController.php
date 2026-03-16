<?php

namespace App\Application\Controller;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class HomeController
{
    
    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
        {
            $view = Twig::fromRequest($request);

            $offres = [
                [
                    "id" => 1,
                    "titre" => "Développeur Web Full-Stack",
                    "entreprise" => "TechCorp",
                    "logo" => "Images/Image_Entreprise_Exemple.png",
                    "duree" => "6 mois",
                    "remuneration" => "600€/mois",
                    "domaine" => "Informatique",
                    "genre" => "H|F",
                    "description" => "Développement d'applications web modernes avec React et Symfony au sein d'une équipe agile dynamique."
                ],
                [
                    "id" => 2,
                    "titre" => "Ingénieur Réseaux & Systèmes",
                    "entreprise" => "NetSolutions",
                    "logo" => "Images/Image_Entreprise_Exemple.png",
                    "duree" => "4 mois",
                    "remuneration" => "500€/mois",
                    "domaine" => "Réseaux",
                    "genre" => "H|F",
                    "description" => "Administration et supervision d'infrastructures réseau dans un environnement cloud hybride."
                ],
                [
                    "id" => 3,
                    "titre" => "Data Analyst Junior",
                    "entreprise" => "DataVision",
                    "logo" => "Images/Image_Entreprise_Exemple.png",
                    "duree" => "5 mois",
                    "remuneration" => "550€/mois",
                    "domaine" => "Data",
                    "genre" => "H|F",
                    "description" => "Analyse de données et création de tableaux de bord pour accompagner la prise de décision stratégique."
                ]
            ];

            return $view->render($response, 'Accueil.html.twig', [
                'offres' => $offres
            ]);
        }
}
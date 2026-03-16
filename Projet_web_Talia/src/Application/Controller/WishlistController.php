<?php

namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class WishlistController
{
    public function wishlist(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $offres = [
            [
                "titre" => "Stage Marketing",
                "entreprise" => "Entreprise XYZ",
                "ville" => "Paris",
                "duree" => "3 mois"
            ],
            [
                "titre" => "Stage Informatique",
                "entreprise" => "Entreprise ABC",
                "ville" => "Lyon",
                "duree" => "6 mois"
            ],
            [
                "titre" => "Stage Design",
                "entreprise" => "Entreprise DEF",
                "ville" => "Marseille",
                "duree" => "4 mois"
            ]
        ];

        return $view->render($response, 'Étudiants/Page_Wishlist.html.twig', [
            'offres' => $offres
        ]);
    }
}
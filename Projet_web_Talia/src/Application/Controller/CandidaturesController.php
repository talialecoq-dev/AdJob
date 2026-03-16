<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class CandidaturesController
{
    public function candidatures(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $candidature   = [ 
[
'offres'=> 'Developpeur Web',
'color' =>'warning',
'etat' => 'En Attente',
'desc' => 'Intégration de maquettes Figma en React/Tailwind et optimisation de la performance Web sur les terminaux mobiles.',
'image' => '\Image\Martin.png',
],
[
'offres'=> 'Developpeur C++',
'color' =>'succes',
'etat' => 'Acceptée',
'desc' => 'Développement des mécaniques de gameplay et optimisation de la gestion de la mémoire pour notre prochain titre AAA. Travail en étroite collaboration avec les Game Designers. ',
'image' => '\Image\Martin.png',
],
[
'offres'=> 'Expert Cybersécurité',
'color' =>'danger',
'etat' => 'Refusée',
'desc' => 'Surveillance des réseaux, audit de vulnérabilité et mise en place de protocoles de sécurité pour la protection des données sensibles.',
'image' => '\Image\Martin.png',
],



        ];
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Candidatures.html.twig', []);
    }
    
    public function candidater(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Page_Modal_Candidature.html.twig', []);
    }
}

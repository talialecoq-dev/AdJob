<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class CandidaturesController
{
    public function candidatures(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
   $view = Twig::fromRequest($request);

    return $view->render($response, 'Candidatures/Candidatures.html.twig', [
'c1' =>[
'nom'=> 'Developpeur Web',
'color' =>'warning',
'statut' => 'En Attente',
'desc' => 'Intégration de maquettes Figma en React/Tailwind et optimisation de la performance Web sur les terminaux mobiles.',
'image' => '\Image\Martin.png',
],
'c2' =>[
'nom'=> 'Developpeur C++',
'color' =>'success',
'statut' => 'Acceptée',
'desc' => 'Développement des mécaniques de gameplay et optimisation de la gestion de la mémoire pour notre prochain titre AAA. Travail en étroite collaboration avec les Game Designers. ',
'image' => '\Image\Martin.png',
],
'c3' => [
'nom'=> 'Expert Cybersécurité',
'color' =>'danger',
'statut' => 'Refusée',
'desc' => 'Surveillance des réseaux, audit de vulnérabilité et mise en place de protocoles de sécurité pour la protection des données sensibles.',
'image' => '\Image\Martin.png',
],



        ]);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Candidatures.html.twig', ['mes_candidatures' => $liste_candidatures]);
    }
    
    public function candidater(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Page_Modal_Candidature.html.twig', []);
    }
}

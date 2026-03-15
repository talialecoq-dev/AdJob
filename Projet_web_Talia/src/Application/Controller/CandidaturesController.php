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
        return $view->render($response, 'Candidatures/Candidatures.html.twig', []);
    }
    
    public function candidater(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Page_Modal_Candidature.html.twig', []);
    }
}

<?php

namespace App\Application\Controller;

use App\Domain\Evaluation;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class EvaluationsController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function listeAvis(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];
        $offre = $this->em->find(\App\Domain\Offre::class, $id);

        if (!$offre) {
            return $response->withStatus(404);
        }

        $evaluations = $this->em->getRepository(Evaluation::class)->findBy(['offre' => $offre]);

        $view = Twig::fromRequest($request);
        return $view->render($response, 'Offres/Page_Avis_Offre.html.twig', [
            'offre' => $offre,
            'evaluations' => $evaluations,
        ]);
    }

    public function ajouterAvis(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        
        return $response->withHeader('Location', '/Offres/' . $args['id'] . '/avis')->withStatus(302);
    }
}
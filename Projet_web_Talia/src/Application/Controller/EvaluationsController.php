<?php

namespace App\Application\Controller;

use App\Domain\Evaluation;
use App\Domain\Offre; // <-- Import de l'entité Offre
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

        // Utilisation de l'entité Offre au lieu du contrôleur
        $offre = $this->em->getRepository(Offre::class)->find($id);
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
        $user = $request->getAttribute('user');  

        if (!$user) {
            return $response->withHeader('Location', '/Login')->withStatus(302);
        }

        $id = (int) $args['id'];
        $offre = $this->em->getRepository(Offre::class)->find($id); // <-- Correction ici

        if (!$offre) {
            return $response->withStatus(404);
        }

        $data = $request->getParsedBody();
        $note = (int) ($data['note'] ?? 0);
        $commentaire = trim($data['commentaire'] ?? '');

        $evaluation = new Evaluation($user, $offre, $note);
        $evaluation->setCommentaire($commentaire);

        $this->em->persist($evaluation);
        $this->em->flush();

        return $response->withHeader('Location', '/Offres/' . $id . '/avis')->withStatus(302);
    }

    public function modifierAvis(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Logique à compléter si besoin
        return $response->withHeader('Location', '/Offres/' . $args['id'] . '/avis')->withStatus(302);
    }
}
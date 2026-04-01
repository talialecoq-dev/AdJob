<?php

namespace App\Application\Controller;

use App\Domain\Candidature;
use App\Domain\Offre;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class CandidaturesController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function candidatures(ServerRequestInterface $request,ResponseInterface $response,array $args): ResponseInterface
    {
        $candidatures = $this->em->getRepository(Candidature::class)->findAll();

        $view = Twig::fromRequest($request);

        return $view->render($response, 'Candidatures/Candidatures.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    public function ajouter(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        
        $data = $request->getParsedBody();
        $competences = $data['competences'] ?? [];

        $candidature = new Candidature(
            trim($data['nom'] ?? ''),
            trim($data['prenom'] ?? ''),
            trim($data['email'] ?? ''),
            trim($data['titre'] ?? 'Offre inconnue'),
            trim($data['remuneration'] ?? ''),
            trim($data['duree'] ?? ''),
            trim($data['domaine'] ?? ''),
            trim($data['entreprise'] ?? ''),
            trim($data['logo'] ?? '') ?: null,
            implode(', ', array_map('trim', $competences)),
            trim($data['description'] ?? ''),
        );

        $this->em->persist($candidature);
        $this->em->flush();

        return $response
            ->withHeader('Location', '/Candidatures')
            ->withStatus(302);
    }

    public function supprimer(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $id = (int) $args['id'];
        $candidature = $this->em->find(Candidature::class, $id);

        if ($candidature) {
            $this->em->remove($candidature);
            $this->em->flush();
        }

        return $response
            ->withHeader('Location', '/Candidatures')
            ->withStatus(302);
    }

    public function candidater(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $id = isset($args['id']) ? (int)$args['id'] : null;
        $offre = null;

        if ($id !== null) {
            $offre = $this->em->find(Offre::class, $id);
        }
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Candidatures/Page_Modal_Candidature.html.twig', [
            'offre' => $offre
        ]);
    }
}
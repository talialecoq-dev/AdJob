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

    public function candidatures(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $request->getAttribute('user');  
        $candidatures = [];

        if ($user) {
            $candidatures = $this->em->getRepository(Candidature::class)->findBy([
                'userId' => $user->getId()  
            ]);
        }

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

        $user = $request->getAttribute('user');

        if (!$user) {
            return $response->withHeader('Location', '/Login')->withStatus(302);
        }

        $data = $request->getParsedBody();
        $files = $request->getUploadedFiles();


        $cvPath = null;
        if (isset($files['cv']) && $files['cv']->getError() === UPLOAD_ERR_OK) {
            $cvFilename = uniqid('cv_') . '_' . $files['cv']->getClientFilename();
            $files['cv']->moveTo(__DIR__ . '/../../../public/uploads/' . $cvFilename);
            $cvPath = 'uploads/' . $cvFilename;
        }


        $lettrePath = null;
        if (isset($files['lettre']) && $files['lettre']->getError() === UPLOAD_ERR_OK) {
            $lettreFilename = uniqid('lettre_') . '_' . $files['lettre']->getClientFilename();
            $files['lettre']->moveTo(__DIR__ . '/../../../public/uploads/' . $lettreFilename);
            $lettrePath = 'uploads/' . $lettreFilename;
        }

        $competences = $data['competences'] ?? [];


        $candidature = new Candidature(
            trim($data['nom'] ?? ''),
            trim($data['prenom'] ?? ''),
            trim($data['email'] ?? ''),
            trim($data['telephone'] ?? ''),
            trim($data['titre'] ?? 'Offre inconnue'),
            trim($data['remuneration'] ?? ''),
            trim($data['duree'] ?? ''),
            trim($data['domaine'] ?? ''),
            trim($data['entreprise'] ?? ''),
            trim($data['logo'] ?? '') ?: null,
            implode(', ', array_map('trim', $competences)),
            trim($data['description'] ?? ''),
            $user->getId(),
            cv: $cvPath,
            lettreMotivation: $lettrePath,
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
        $user = $request->getAttribute('user');
        $id = (int) $args['id'];
        $candidature = $this->em->find(Candidature::class, $id);

        if ($candidature && $user && $candidature->getUserId() === $user->getId()) {
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

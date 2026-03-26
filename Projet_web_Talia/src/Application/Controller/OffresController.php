<?php

namespace App\Application\Controller;

use App\Domain\Offre;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class OffresController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    
    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $repository = $this->em->getRepository(Offre::class);
        $offres     = $repository->findAll();

        return $view->render($response, 'Offres/Page_Liste_Offres.html.twig', [
            'offres' => $offres
        ]);
    }

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();

            $repository = $this->em->getRepository(Offre::class);

            $errors = [];

            if (empty(trim($data['titre'] ?? '')))
                $errors['titre'] = "Le nom de l'offre est obligatoire.";

            if (empty(trim($data['entreprise'] ?? '')))
                $errors['entreprise'] = "Le nom de l'entreprise est obligatoire.";

            if (empty($data['duree'] ?? ''))
                $errors['duree'] = 'Veuillez sélectionner une durée.';

            if (empty($data['domaine'] ?? ''))
                $errors['domaine'] = 'Veuillez sélectionner un domaine.';

            if (empty(trim($data['remuneration'] ?? '')))
                $errors['remuneration'] = 'La rémunération est obligatoire.';

            if (empty(trim($data['description'] ?? '')))
                $errors['description'] = 'La description est obligatoire.';

            $competences = array_values(array_filter(
                array_map('trim', $data['competences'] ?? []),
                fn($c) => $c !== ''
            ));

            if (count($competences) < 3)
                $errors['competences'] = 'Veuillez renseigner au moins 3 compétences.';

            if (!empty($errors)) {
                $offres = $repository->findAll();
                $data['competences'] = $competences;

                return $view->render($response, 'Offres/Page_Liste_Offres.html.twig', [
                    'offres'  => $offres,
                    'errors'  => $errors,
                    'old'     => $data,
                ]);
            }

            $offre = new Offre(
                $data['titre'],
                $data['entreprise'],
                $data['duree'],
                $data['remuneration'],
                $data['domaine'],
                implode(', ', $competences),
                $data['description'],
                $data['logo'] ?? null
            );

            $this->em->persist($offre);
            $this->em->flush();

            return $response->withHeader('Location', '/Offres')->withStatus(302);
        }

        return $view->render($response, 'Offres/Page_Modal_Ajout_Offre.html.twig', []);
    }
}
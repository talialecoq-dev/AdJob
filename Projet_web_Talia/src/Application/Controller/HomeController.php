<?php

namespace App\Application\Controller;

use App\Domain\Offre;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class HomeController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $parPage = 9;

        
        $queryParams = $request->getQueryParams();
        $recherche   = trim($queryParams['recherche'] ?? '');
        $domaine     = trim($queryParams['domaine'] ?? '');
        $duree       = trim($queryParams['duree'] ?? '');
        $ville       = trim($queryParams['ville'] ?? '');
        $page        = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $offset      = ($page - 1) * $parPage;

        $repository = $this->em->getRepository(Offre::class);

        
        $qb = $repository->createQueryBuilder('o');

        if ($recherche !== '') {
            $qb->andWhere('o.titre LIKE :recherche OR o.description LIKE :recherche OR o.entreprise LIKE :recherche')
            ->setParameter('recherche', '%' . $recherche . '%');
        }

        if ($domaine !== '') {
            $qb->andWhere('o.domaine = :domaine')
            ->setParameter('domaine', $domaine);
        }

        if ($duree !== '') {
            $qb->andWhere('o.duree = :duree')
            ->setParameter('duree', $duree);
        }

        
        if ($ville !== '') {
            $qb->andWhere('o.ville = :ville')
            ->setParameter('ville', $ville);
        }

        
        $countQb = clone $qb;
        $total = $countQb->select('COUNT(o.id)')->getQuery()->getSingleScalarResult();

        
        $offres = $qb->select('o')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($parPage)
            ->getQuery()
            ->getResult();

        return $view->render($response, 'Accueil.html.twig', [
            'offres'     => $offres,
            'page'       => $page,
            'totalPages' => (int) ceil($total / $parPage),
            'total'      => $total,
            'wishlist'   => $_SESSION['wishlist'] ?? [],
            'filtres'    => [
                'recherche' => $recherche,
                'domaine'   => $domaine,
                'duree'     => $duree,
                'ville'     => $ville,
            ],
        ]);
    }

        public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $data = $request->getParsedBody();

        $errors = [];

        if (empty(trim($data['titre'] ?? '')))
            $errors['titre'] = 'Le nom de l\'offre est obligatoire.';

        if (empty(trim($data['entreprise'] ?? '')))
            $errors['entreprise'] = 'Le nom de l\'entreprise est obligatoire.';

        if (empty($data['duree'] ?? ''))
            $errors['duree'] = 'Veuillez sélectionner une durée.';

        if (empty($data['domaine'] ?? ''))
            $errors['domaine'] = 'Veuillez sélectionner un domaine.';

        if (empty(trim($data['remuneration'] ?? '')))
            $errors['remuneration'] = 'La rémunération est obligatoire.';

        if (empty(trim($data['description'] ?? '')))
            $errors['description'] = 'La description est obligatoire.';

        // Validation compétences — min 3 non vides
        $competences = array_values(array_filter(
            array_map('trim', $data['competences'] ?? []),
            fn($c) => $c !== ''
        ));

        if (count($competences) < 3)
            $errors['competences'] = 'Veuillez renseigner au moins 3 compétences.';

        // Si erreurs → on réaffiche avec les anciennes valeurs
        if (!empty($errors)) {
            $parPage = 9;
            $repository = $this->em->getRepository(Offre::class);

            $total = $repository->createQueryBuilder('o')
                ->select('COUNT(o.id)')
                ->getQuery()
                ->getSingleScalarResult();

            $offres = $repository->createQueryBuilder('o')
                ->orderBy('o.id', 'DESC')
                ->setFirstResult(0)
                ->setMaxResults($parPage)
                ->getQuery()
                ->getResult();

            $data['competences'] = $competences;

            return $view->render($response, 'Accueil.html.twig', [
                'offres'     => $offres,
                'page'       => 1,
                'totalPages' => (int) ceil($total / $parPage),
                'total'      => $total,
                'errors'     => $errors,
                'old'        => $data,
                'wishlist'   => $_SESSION['wishlist'] ?? [],
            ]);
        }

        $offre = new Offre(
            $data['titre'],
            $data['entreprise'],
            $data['duree'],
            $data['remuneration'],
            $data['domaine'],
            implode(', ', $competences),  // stocké en string dans la BDD
            $data['description'],
            $data['logo'] ?? null
        );

        $this->em->persist($offre);
        $this->em->flush();

        return $response->withHeader('Location', '/')->withStatus(302);
    }

        public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];
        $offre = $this->em->find(Offre::class, $id);

        if ($offre) {
            $this->em->remove($offre);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }


    
}
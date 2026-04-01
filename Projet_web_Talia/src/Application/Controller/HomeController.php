<?php

namespace App\Application\Controller;

use App\Domain\Wishlist;
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
        $conn = $this->em->getConnection();

        $parPage     = 9;
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
            $qb->andWhere('o.domaine = :domaine')->setParameter('domaine', $domaine);
        }
        if ($duree !== '') {
            $qb->andWhere('o.duree = :duree')->setParameter('duree', $duree);
        }
        if ($ville !== '') {
            $qb->andWhere('o.ville = :ville')->setParameter('ville', $ville);
        }

        $countQb = clone $qb;
        $total   = $countQb->select('COUNT(o.id)')->getQuery()->getSingleScalarResult();

        $offres = $qb->select('o')
            ->orderBy('o.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($parPage)
            ->getQuery()
            ->getResult();

        
        $repartitionDuree = $conn->fetchAllAssociative('
            SELECT duree, COUNT(*) as total
            FROM offres
            GROUP BY duree
            ORDER BY total DESC
        ');

        $topWishlist = $conn->fetchAllAssociative('
            SELECT o.titre, o.entreprise, COUNT(w.offre_id) as nb_wishlist
            FROM wishlists w
            JOIN offres o ON o.id = w.offre_id
            GROUP BY w.offre_id, o.titre, o.entreprise
            ORDER BY nb_wishlist DESC
            LIMIT 5
        ');

        $totalOffres         = $conn->fetchOne('SELECT COUNT(*) FROM offres');
        $moyenneCandidatures = $conn->fetchOne('
            SELECT ROUND(COUNT(*) / NULLIF((SELECT COUNT(*) FROM offres), 0), 1)
            FROM candidatures
        ') ?? 0;

        
        $user = $request->getAttribute('user');
        $wishlistIds = [];
        if ($user) {
            $rows = $conn->fetchAllAssociative(
                'SELECT offre_id FROM wishlists WHERE user_id = ?',
                [$user->getId()]
            );
            $wishlistIds = array_column($rows, 'offre_id');
        }

        return $view->render($response, 'Accueil.html.twig', [
            'offres'              => $offres,
            'page'                => $page,
            'totalPages'          => (int) ceil($total / $parPage),
            'total'               => $total,
            'wishlist'            => $wishlistIds,
            'filtres'             => compact('recherche', 'domaine', 'duree', 'ville'),
            'repartitionDuree'    => $repartitionDuree,
            'topWishlist'         => $topWishlist,
            'totalOffres'         => $totalOffres,
            'moyenneCandidatures' => $moyenneCandidatures,
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

        
        $competences = array_values(array_filter(
            array_map('trim', $data['competences'] ?? []),
            fn($c) => $c !== ''
        ));

        if (count($competences) < 3)
            $errors['competences'] = 'Veuillez renseigner au moins 3 compétences.';

        
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
                'wishlist' => array_map(fn($item) => $item->getOffre()->getId(),$this->em->getRepository(Wishlist::class)->findAll()),
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
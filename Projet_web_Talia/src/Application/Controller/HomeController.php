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
        $page    = isset($args['page']) ? (int)$args['page'] : 1;
        $offset  = ($page - 1) * $parPage;

        $repository = $this->em->getRepository(Offre::class);

        $total = $repository->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $offres = $repository->createQueryBuilder('o')
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
        ]);
    }
}
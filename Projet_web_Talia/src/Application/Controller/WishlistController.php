<?php

namespace App\Application\Controller;

use App\Domain\Offre;
use App\Domain\Wishlist;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class WishlistController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $id   = (int) $args['id'];

        if ($user) {
            $conn = $this->em->getConnection();

            
            $exists = $conn->fetchOne(
                'SELECT COUNT(*) FROM wishlists WHERE user_id = ? AND offre_id = ?',
                [$user->getId(), $id]
            );

            if (!$exists) {
                $conn->executeStatement(
                    'INSERT INTO wishlists (user_id, offre_id) VALUES (?, ?)',
                    [$user->getId(), $id]
                );
            }
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function retirer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $id   = (int) $args['id'];

        if ($user) {
            $this->em->getConnection()->executeStatement(
                'DELETE FROM wishlists WHERE user_id = ? AND offre_id = ?',
                [$user->getId(), $id]
            );
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function wishlist(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $user = $request->getAttribute('user');
        $offres = [];

        if ($user) {
            $conn = $this->em->getConnection();
            $rows = $conn->fetchAllAssociative(
                'SELECT offre_id FROM wishlists WHERE user_id = ?',
                [$user->getId()]
            );
            $ids = array_column($rows, 'offre_id');

            foreach ($ids as $offreId) {
                $offre = $this->em->find(\App\Domain\Offre::class, $offreId);
                if ($offre) {
                    $offres[] = $offre;
                }
            }
        }

        return $view->render($response, 'Étudiants/Page_Wishlist.html.twig', [
            'offres' => $offres,
        ]);
    }



}
<?php

namespace App\Application\Controller;

use App\Domain\Offre;
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

    public function wishlist(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $ids = $_SESSION['wishlist'] ?? [];
        $offres = [];

        foreach ($ids as $id) {
            $offre = $this->em->find(Offre::class, $id);
            if ($offre) $offres[] = $offre;
        }

        return $view->render($response, 'Étudiants/Page_Wishlist.html.twig', [
            'offres' => $offres,
        ]);
    }

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];

        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }

        if (!in_array($id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $id;
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function retirer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];

        $_SESSION['wishlist'] = array_values(
            array_filter($_SESSION['wishlist'] ?? [], fn($i) => $i !== $id)
        );

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}
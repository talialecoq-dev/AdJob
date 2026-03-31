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

    public function wishlist(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
         $wishlistItems = $this->em->getRepository(Wishlist::class)->findAll();

        $view = Twig::fromRequest($request);
      
        return $view->render($response, 'Étudiants/Page_Wishlist.html.twig', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $idOffre = (int) $args['id'];

         $offre = $this->em->find(Offre::class, $idOffre);

        if ($offre) {
        $wishlist = new Wishlist($offre);
        $this->em->persist($wishlist);
        $this->em->flush();
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

 public function retirer(
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $args
): ResponseInterface {
    $id = (int) $args['id'];

    $wishlist = $this->em->find(Wishlist::class, $id); // Wishlist pas Offre !

    if ($wishlist) {
        $this->em->remove($wishlist); // $wishlist pas $offres !
        $this->em->flush();
    }

    return $response->withHeader('Location', '/')->withStatus(302);
}
}
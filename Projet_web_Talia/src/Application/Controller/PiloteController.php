<?php

namespace App\Application\Controller;

use App\Domain\Pilote;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class PiloteController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type' => 'Pilote'
        ]);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $repository = $this->em->getRepository(Pilote::class);
        $pilotes    = $repository->findAll();

        return $view->render($response, 'Pilotes/Page_Liste_Pilote.html.twig', [
            'pilotes' => $pilotes
        ]);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $id     = (int) $args['id'];
        $pilote = $this->em->find(Pilote::class, $id);

        return $view->render($response, 'Pilotes/Page_Modifier_Pilote.html.twig', [
            'pilote' => $pilote
        ]);
    }

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        
        $imageName = null;
        if (isset($_FILES['image_profil']) && $_FILES['image_profil']['error'] === 0) {
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $extension = strtolower(pathinfo($_FILES['image_profil']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions)) {
                echo "Erreur : seuls les fichiers PNG, JPG ou JPEG sont autorisés.";
                exit;
            }

            $imageName = uniqid() . '.' . $extension;
            move_uploaded_file(
                $_FILES['image_profil']['tmp_name'],
                __DIR__ . '/../../../../public/uploads/' . $imageName
            );
        }

        $pilote = new Pilote(
            $data['prenom']      ?? '',
            $data['nom']         ?? '',
            $data['email']       ?? '',
            $data['campus']      ?? '',
            $data['localisation'] ?? '',
        );

        $this->em->persist($pilote);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id     = (int) $args['id'];
        $pilote = $this->em->find(Pilote::class, $id);

        if ($pilote) {
            $this->em->remove($pilote);
            $this->em->flush();
        }

        
        return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id   = (int) $args['id'];
        $data = $request->getParsedBody();

        $pilote = $this->em->find(Pilote::class, $id);

        if ($pilote) {
            
            $pilote->setPrenom($data['prenom']           ?? '');
            $pilote->setNom($data['nom']                 ?? '');
            $pilote->setEmail($data['email']             ?? '');
            $pilote->setCampus($data['campus']           ?? '');
            $pilote->setLocalisation($data['localisation'] ?? '');

            $this->em->flush();
        }

        
        return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
    }
}
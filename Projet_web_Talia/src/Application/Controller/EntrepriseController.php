<?php

namespace App\Application\Controller;

use App\Domain\Entreprise;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class EntrepriseController
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
            'type' => 'Entreprise'
        ]);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $repository  = $this->em->getRepository(Entreprise::class);
        $entreprises = $repository->findAll();
        return $view->render($response, 'Entreprises/Page_Liste_Entreprises.html.twig', ['entreprises' => $entreprises]);
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];
        $entreprise = $this->em->find(Entreprise::class, $id);

        if ($entreprise) {
            $this->em->remove($entreprise);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/entreprise/Liste-Entreprises')->withStatus(302);
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $parPage = 9;
        $page    = isset($args['page']) ? (int) $args['page'] : 1;
        $offset  = ($page - 1) * $parPage;

        $repository = $this->em->getRepository(Entreprise::class);

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

        return $view->render($response, 'Page_Liste_Entreprises.html.twig', [
            'offres'     => $offres,
            'page'       => $page,
            'totalPages' => (int) ceil($total / $parPage),
            'total'      => $total,
        ]);
    }

    public function traiterInscription(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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

        $entreprise = new Entreprise(
            $data['nom_entreprise'] ?? '',
            $data['secteur']        ?? '',
            $data['email']          ?? '',
            $data['site_web']       ?? '',
            $imageName,
        );

        $this->em->persist($entreprise);
        $this->em->flush();

        return $response
            ->withHeader('Location', '/entreprise/Liste-Entreprises')
            ->withStatus(302);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $id = (int) $args['id'];
        $entreprise = $this->em->find(Entreprise::class, $id);

        return $view->render($response, 'Entreprises/Page_Modifier_Entreprise.html.twig', [
            'entreprise' => $entreprise,
            'type' => 'Entreprise_Modifier'
        ]);
    }

    public function recherche_entreprise(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $repository  = $this->em->getRepository(Entreprise::class);
        $entreprises = $repository->findAll();

        return $view->render($response, 'Entreprises/Page_Consulter_Entreprises.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id   = (int) $args['id'];
        $data = $request->getParsedBody();

        $entreprise = $this->em->find(Entreprise::class, $id);

        if ($entreprise) {
            
            $entreprise->setNom($data['nom_entreprise'] ?? '');
            $entreprise->setSecteur($data['secteur']    ?? '');
            $entreprise->setEmail($data['email']        ?? '');
            $entreprise->setSiteWeb($data['site_web']   ?? '');

            $this->em->flush();
        }

        
        return $response->withHeader('Location', '/entreprise/Liste-Entreprises')->withStatus(302);
    }
}
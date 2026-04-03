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

    private function traiterUploadImage(ServerRequestInterface $request): ?string
    {
        $files = $request->getUploadedFiles();

        if (!isset($files['image_profil']) || $files['image_profil']->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $extension = strtolower(pathinfo($files['image_profil']->getClientFilename(), PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            return null;
        }

        $imageName = 'logo_' . uniqid() . '.' . $extension;
        $files['image_profil']->moveTo(
            __DIR__ . '/../../../public/uploads/' . $imageName
        );

        return $imageName;
    }

    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $campusList = $this->em->getRepository(\App\Domain\Campus::class)->findAll();
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Entreprise',
            'campusList' => $campusList,
        ]);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view        = Twig::fromRequest($request);
        $entreprises = $this->em->getRepository(Entreprise::class)->findAll();
        return $view->render($response, 'Entreprises/Page_Liste_Entreprises.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id         = (int) $args['id'];
        $entreprise = $this->em->find(Entreprise::class, $id);

        if ($entreprise) {
            $this->em->remove($entreprise);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/entreprise/Liste-Entreprises')->withStatus(302);
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view    = Twig::fromRequest($request);
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
        $data      = $request->getParsedBody();
        $imageName = $this->traiterUploadImage($request);

        $entreprise = new Entreprise(
            $data['nom_entreprise'] ?? '',
            $data['secteur']        ?? '',
            $data['email']          ?? '',
            $data['site_web']       ?? '',
            $imageName,
        );

        if (!empty($data['campus'])) {
            $campus = $this->em->getRepository(\App\Domain\Campus::class)
                ->findOneBy(['nomVille' => $data['campus']]);
            if ($campus) {
                $entreprise->setCampus($campus);
            }
        }

        $this->em->persist($entreprise);
        $this->em->flush();

        return $response->withHeader('Location', '/entreprise/Liste-Entreprises')->withStatus(302);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $id         = (int) $args['id'];
        $entreprise = $this->em->find(Entreprise::class, $id);
        $campusList = $this->em->getRepository(\App\Domain\Campus::class)->findAll();

        return $view->render($response, 'Entreprises/Page_Modifier_Entreprise.html.twig', [
            'entreprise' => $entreprise,
            'type'       => 'Entreprise_Modifier',
            'campusList' => $campusList,
        ]);
    }

    public function recherche_entreprise(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view        = Twig::fromRequest($request);
        $entreprises = $this->em->getRepository(Entreprise::class)->findAll();

        return $view->render($response, 'Entreprises/Page_Consulter_Entreprises.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id         = (int) $args['id'];
        $data       = $request->getParsedBody();
        $entreprise = $this->em->find(Entreprise::class, $id);

        if ($entreprise) {
            $entreprise->setNom($data['nom_entreprise'] ?? '');
            $entreprise->setSecteur($data['secteur']    ?? '');
            $entreprise->setEmail($data['email']        ?? '');
            $entreprise->setSiteWeb($data['site_web']   ?? '');

            $newImage = $this->traiterUploadImage($request);
            if ($newImage) {
                $entreprise->setImage($newImage);
            }

            if (!empty($data['campus'])) {
                $campus = $this->em->getRepository(\App\Domain\Campus::class)
                    ->findOneBy(['nomVille' => $data['campus']]);
                if ($campus) {
                    $entreprise->setCampus($campus);
                }
            }

            $this->em->flush();
        }

        return $response->withHeader('Location', '/entreprise/Liste-Entreprises')->withStatus(302);
    }

    public function offresParEntreprise(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view          = Twig::fromRequest($request);
        $nomEntreprise = urldecode($args['nom']);

        $offres = $this->em->getRepository(\App\Domain\Offre::class)
            ->createQueryBuilder('o')
            ->where('LOWER(o.entreprise) = LOWER(:nom)')
            ->setParameter('nom', $nomEntreprise)
            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $view->render($response, 'Entreprises/Page_Offres_Entreprise.html.twig', [
            'offres'     => $offres,
            'entreprise' => $nomEntreprise,
        ]);
    }
}

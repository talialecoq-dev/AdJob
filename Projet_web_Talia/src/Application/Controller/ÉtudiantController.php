<?php

namespace App\Application\Controller;

use App\Domain\Etudiant;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ÉtudiantController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $parPage = 100;
        $page    = max(1, (int) ($args['page'] ?? 1));
        $offset  = ($page - 1) * $parPage;

        $repository = $this->em->getRepository(Etudiant::class);

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

    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type' => 'Etudiants_ajout'
        ]);
    }

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        // Validation du fichier uploadé avant toute insertion
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

        // CORRECTION : ordre des paramètres aligné sur le constructeur Etudiant(prenom, nom, email, adresse, ville, region)
        $etudiant = new Etudiant(
            $data['prenom']  ?? '',
            $data['nom']     ?? '',
            $data['email']   ?? '',
            $data['adresse'] ?? '',
            $data['ville']   ?? '',
            $data['region']  ?? '',
            $imageName
        );

        $this->em->persist($etudiant);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id      = (int) $args['id'];
        $etudiant = $this->em->find(Etudiant::class, $id);

        if ($etudiant) {
            $this->em->remove($etudiant);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view     = Twig::fromRequest($request);
        $etudiants = $this->em->getRepository(Etudiant::class)->findAll();

        return $view->render($response, 'Étudiants/Page_Liste_Étudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $id      = (int) $args['id'];
        $etudiant = $this->em->find(Etudiant::class, $id);

        return $view->render($response, 'Étudiants/Page_Modifier_Étudiant.html.twig', [
            'etudiant' => $etudiant
        ]);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id   = (int) $args['id'];
        $data = $request->getParsedBody();

        $etudiant = $this->em->find(Etudiant::class, $id);

        if ($etudiant) {
            $etudiant->setPrenom($data['prenom']  ?? '');
            $etudiant->setNom($data['nom']        ?? '');
            $etudiant->setEmail($data['email']    ?? '');
            $etudiant->setVille($data['ville']    ?? '');
            $etudiant->setAdresse($data['adresse'] ?? '');
            $etudiant->setRegion($data['region']  ?? '');

            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }
}
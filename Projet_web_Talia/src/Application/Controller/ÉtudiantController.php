<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Doctrine\ORM\EntityManager;
use App\Domain\Etudiant;

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
        $page = max(1, (int)($args['page'] ?? 1)); 
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
        $this->verifierUpload();

        $etudiant = new Etudiant(
            $data['prenom'] ?? '',
            $data['nom'] ?? '',
            $data['email'] ?? '',
            $data['ville'] ?? '',   
            $data['adresse'] ?? '', 
            $data['region'] ?? '',  
        );

        $this->em->persist($etudiant);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int) $args['id'];
        $etudiant = $this->em->find(Etudiant::class, $id);

        if ($etudiant) {
            $this->em->remove($etudiant);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $etudiants = $this->em->getRepository(Etudiant::class)->findAll();

        return $view->render($response, 'Étudiants/Page_Liste_Étudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Étudiants/Page_Modifier_Étudiant.html.twig', []);
    }

    public function verifierUpload(): void
    {
        foreach ($_FILES as $file) {
            if ($file['error'] === 0) {
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                if (!in_array($extension, $allowedExtensions)) {
                    echo "Erreur : seuls les fichiers PNG, JPG ou JPEG sont autorisés.";
                    exit;
                }
            }
        }
    }
}
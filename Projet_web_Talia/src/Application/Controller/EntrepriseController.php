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
        return $view->render($response, 'Entreprises/Page_Liste_Entreprises.html.twig', ['entreprises' => $entreprises ]);
    }

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Supprimer_Entreprise.html.twig', []);
    }
    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $parPage = 9;
        $page    = isset($args['page']) ? (int)$args['page'] : 1;
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

       
        $entreprise = new Entreprise(
            $data['nom_entreprise'] ?? '',
            $data['secteur']        ?? '',
            $data['email']          ?? '',
            $data['site_web']       ?? '', 
            $data['image_entreprise']       ?? null, 
        );

        // On persiste et on flush comme lui
        $this->em->persist($entreprise);
        $this->em->flush();

   
        foreach($_FILES as $file){
            if($file['error'] === 0){
                $allowedExtensions = ['png','jpg','jpeg'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if(!in_array($extension, $allowedExtensions)){
                    echo "Erreur : seuls les fichiers PNG, JPG ou JPEG sont autorisés.";
                    exit;
                }
            }
        }
        
        return $response
            ->withHeader('Location', '/Liste-Entreprises')
            ->withStatus(302);
    }

    public function recherche_entreprise(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        // On utilise le repository pour récupérer les vraies données (Comme son $repository->findAll())
        $repository = $this->em->getRepository(Entreprise::class);
        $entreprises = $repository->findAll();

        return $view->render($response, 'Entreprises/Page_Consulter_Entreprises.html.twig', [
            'entreprises' => $entreprises
        ]);
    }
}
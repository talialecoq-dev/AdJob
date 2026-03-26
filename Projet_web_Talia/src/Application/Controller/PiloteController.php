<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Doctrine\ORM\EntityManager;
use App\Domain\Pilote;

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
        return $view->render($response, 'Pilotes/Page_Inscription_Pilote.html.twig', [  'type' => 'Pilote_ajout']);
    }


    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
                $view = Twig::fromRequest($request);
                $repository = $this->em->getRepository(Pilote::class);
                $pilotes = $repository->findAll(); 
    return $view->render($response, 'Pilotes/Page_Liste_Pilote.html.twig', ['pilotes'=>$pilotes ]);

    }
    
    
    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
{
    $view = Twig::fromRequest($request);

    $id = (int) $args['id'];
    $pilote = $this->em->find(Pilote::class, $id);

    return $view->render($response, 'Pilotes/Page_Modifier_Pilote.html.twig', [
        'pilote' => $pilote,
        'type'   => 'Pilote_Modif'
    ]);
}

    public function ajouter(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->verifierUpload();

        $pilote = new Pilote(
            $data['prenom'] ?? '',
            $data['nom'] ?? '',
            $data['email'] ?? '',
            $data['campus'] ?? '',
            $data['localisation'] ?? '',
        );

    $this->em->persist($pilote);
    $this->em->flush();
  return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
}
    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
    $id = (int) $args['id'];
    $pilote = $this->em->find(Pilote::class,$id);

    if ($pilote) {
        $this->em->remove($pilote);
        $this->em->flush();
    }
    return $response->withHeader('Location','/')->withStatus(302);
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
    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
{
    $id = (int) $args['id'];
    $data = $request->getParsedBody();

    $pilote = $this->em->find(Pilote::class, $id);

    if ($pilote) {
        $pilote->setPrenom($data['prenom'] ?? '');
        $pilote->setNom($data['nom'] ?? '');
        $pilote->setEmail($data['email'] ?? '');
        $pilote->setcampus($data['campus'] ?? '');
        $pilote->setAdresse($data['localisation'] ?? '');
        $this->em->flush();
    }

    return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
}
}




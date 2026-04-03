<?php

namespace App\Application\Controller;

use App\Domain\Campus;
use App\Domain\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ProfilController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function profil(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $user = $request->getAttribute('user');

        return $view->render($response, 'Bases/Page_Profil.html.twig', [
            'user' => $user,
        ]);
    }

    public function modifierProfil(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $user = $request->getAttribute('user');
        $campusList = $this->em->getRepository(Campus::class)->findAll();

        return $view->render($response, 'Bases/Page_Modifier_Profil.html.twig', [
            'user'       => $user,
            'campusList' => $campusList,
        ]);
    }

    public function updateProfil(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();

        if ($user) {
            $user->setNom(trim($data['nom'] ?? $user->getNom()));
            $user->setPrenom(trim($data['prenom'] ?? $user->getPrenom()));
            $user->setEmail(trim($data['email'] ?? $user->getEmail()));

            
            if ($user->getRole()->value === 'etudiant') {
                $user->setAdresse($data['adresse'] ?? null);
                $user->setVille($data['ville'] ?? null);
                $user->setRegion($data['region'] ?? null);
            }

            
            if ($user->getRole()->value === 'pilote') {
                $user->setLocalisation($data['localisation'] ?? null);
            }

            
            if (!empty($data['mot_de_passe'])) {
                $user->setMotDePasse(password_hash($data['mot_de_passe'], PASSWORD_BCRYPT));
            }

            
            if (isset($_FILES['image_profil']) && $_FILES['image_profil']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename   = uniqid() . '_' . basename($_FILES['image_profil']['name']);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['image_profil']['tmp_name'], $targetPath)) {
                    $user->setLogo($filename);
                }
            }

           
            if (!empty($data['campus'])) {
                $campus = $this->em->getRepository(Campus::class)->findOneBy(['nomVille' => $data['campus']]);
                if ($campus) {
                    $user->setCampus($campus);
                }
            }

            $this->em->flush();
        }

        return $response->withHeader('Location', '/Profil')->withStatus(302);
    }
}

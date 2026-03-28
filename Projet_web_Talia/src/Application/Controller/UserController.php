<?php

namespace App\Application\Controller;

use App\Domain\User;
use App\Domain\Role;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class UserController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

   

    public function inscriptionEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type' => 'Etudiants_ajout'
        ]);
    }

    public function ajouterEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $imageName = $this->traiterUpload();
     
        $user = new User(
            $data['nom']     ?? '',
            $data['prenom']  ?? '',
            $data['email']   ?? '',
            password_hash($data['mot_de_passe'] ?? uniqid(), PASSWORD_BCRYPT),
            $data['adresse'] ?? null,
            $data['ville']   ?? null,
            $data['region']  ?? null,
            null,
            $imageName
        );
        $user->setRole(Role::ETUDIANT);

        $this->em->persist($user);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

    public function listeEtudiants(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $etudiants = $this->em->getRepository(User::class)->findBy(['role' => Role::ETUDIANT]);

        return $view->render($response, 'Étudiants/Page_Liste_Étudiant.html.twig', [
            'etudiants' => $etudiants
        ]);
    }

    public function modifierEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $user = $this->em->find(User::class, (int) $args['id']);

        return $view->render($response, 'Étudiants/Page_Modifier_Étudiant.html.twig', [
            'etudiant' => $user
        ]);
    }

    public function updateEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $user->setNom($data['nom']       ?? '');
            $user->setPrenom($data['prenom'] ?? '');
            $user->setEmail($data['email']   ?? '');
            $user->setAdresse($data['adresse'] ?? null);
            $user->setVille($data['ville']   ?? null);
            $user->setRegion($data['region'] ?? null);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

    public function supprimerEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user_Etudiant = $this->em->find(User::class, (int) $args['id']);

        if ($user_Etudiant) {
            $this->em->remove($user_Etudiant);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Étudiants')->withStatus(302);
    }

  

    public function inscriptionPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type' => 'Pilote'
        ]);
    }

    public function ajouterPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $imageName = $this->traiterUpload();

        $role = Role::PILOTE;

        $user_Pilote = new User(
            $data['nom']    ?? '',
            $data['prenom'] ?? '',
            $data['email']  ?? '',
            password_hash($data['mot_de_passe'] ?? uniqid(), PASSWORD_BCRYPT),
            null,
            null,
            null,
            $data['localisation'] ?? null,
            $imageName
        );
        $user_Pilote->setRole($role);

        
        if (!empty($data['campus'])) {
            $campus = $this->em->getRepository(\App\Domain\Campus::class)
                ->findOneBy(['nomVille' => $data['campus']]);
            if ($campus) {
                $user_Pilote->setCampus($campus);
            }
        }

        $this->em->persist($user_Pilote);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
    }

    public function listePilotes(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view = Twig::fromRequest($request);

        $user = $this->em->getRepository(User::class)->findBy(['role' => Role::PILOTE]);

        return $view->render($response, 'Pilotes/Page_Liste_Pilote.html.twig', [
            'pilotes' => $user
        ]);
    }

    public function modifierPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        $user = $this->em->find(User::class, (int) $args['id']);

        return $view->render($response, 'Pilotes/Page_Modifier_Pilote.html.twig', [
            'pilote' => $user
        ]);
    }

    public function updatePilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $user->setNom($data['nom']               ?? '');
            $user->setPrenom($data['prenom']         ?? '');
            $user->setEmail($data['email']           ?? '');
            $user->setLocalisation($data['localisation'] ?? null);

            if (!empty($data['campus'])) {
                $campus = $this->em->getRepository(\App\Domain\Campus::class)
                    ->findOneBy(['nomVille' => $data['campus']]);
                if ($campus) {
                    $user->setCampus($campus);
                }
            }

            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
    }

    public function supprimerPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Pilotes')->withStatus(302);
    }

   

    private function traiterUpload(): ?string
    {
        if (!isset($_FILES['image_profil']) || $_FILES['image_profil']['error'] !== 0) {
            return null;
        }

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

        return $imageName;
    }
}
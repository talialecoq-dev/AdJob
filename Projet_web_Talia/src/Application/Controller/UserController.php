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
        $campusList = $this->em->getRepository(\App\Domain\Campus::class)->findAll();
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Etudiants_ajout',
            'campusList' => $campusList,
        ]);
    }

    public function ajouterEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data      = $request->getParsedBody();
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

        if (!empty($data['campus'])) {
            $campus = $this->em->getRepository(\App\Domain\Campus::class)
                ->findOneBy(['nomVille' => $data['campus']]);
            if ($campus) {
                $user->setCampus($campus);
            }
        }

        $this->em->persist($user);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function modifierEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $user       = $this->em->find(User::class, (int) $args['id']);
        $campusList = $this->em->getRepository(\App\Domain\Campus::class)->findAll();

        return $view->render($response, 'Étudiants/Page_Modifier_Étudiant.html.twig', [
            'etudiant'   => $user,
            'campusList' => $campusList,
        ]);
    }

    public function updateEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $user->setNom($data['nom']         ?? '');
            $user->setPrenom($data['prenom']   ?? '');
            $user->setEmail($data['email']     ?? '');
            $user->setAdresse($data['adresse'] ?? null);
            $user->setVille($data['ville']     ?? null);
            $user->setRegion($data['region']   ?? null);

            if (!empty($data['campus'])) {
                $campus = $this->em->getRepository(\App\Domain\Campus::class)
                    ->findOneBy(['nomVille' => $data['campus']]);
                if ($campus) {
                    $user->setCampus($campus);
                }
            }

            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function supprimerEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function inscriptionPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $campusList = $this->em->getRepository(\App\Domain\Campus::class)->findAll();
        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Pilote',
            'campusList' => $campusList,
        ]);
    }

    public function ajouterPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data      = $request->getParsedBody();
        $imageName = $this->traiterUpload();

        $user = new User(
            $data['nom']    ?? '',
            $data['prenom'] ?? '',
            $data['email']  ?? '',
            password_hash($data['mot_de_passe'] ?? uniqid(), PASSWORD_BCRYPT),
            null, null, null,
            $data['localisation'] ?? null,
            $imageName
        );
        $user->setRole(Role::PILOTE);

        if (!empty($data['campus'])) {
            $campus = $this->em->getRepository(\App\Domain\Campus::class)
                ->findOneBy(['nomVille' => $data['campus']]);
            if ($campus) {
                $user->setCampus($campus);
            }
        }

        $this->em->persist($user);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function modifierPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $user       = $this->em->find(User::class, (int) $args['id']);
        $campusList = $this->em->getRepository(\App\Domain\Campus::class)->findAll();

        return $view->render($response, 'Pilotes/Page_Modifier_Pilote.html.twig', [
            'pilote'     => $user,
            'campusList' => $campusList,
        ]);
    }

    public function updatePilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $user->setNom($data['nom']                   ?? '');
            $user->setPrenom($data['prenom']             ?? '');
            $user->setEmail($data['email']               ?? '');
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

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function supprimerPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function listeUsers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view  = Twig::fromRequest($request);
        $users = $this->em->getRepository(User::class)->findAll();

        return $view->render($response, 'Users/Page_Liste_User.html.twig', [
            'users' => $users
        ]);
    }

    // Étudiants du même campus que le pilote connecté
    public function etudiantsDuCampus(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view   = Twig::fromRequest($request);
        $pilote = $request->getAttribute('user');

        $etudiants = [];
        if ($pilote && $pilote->getCampus()) {
            $etudiants = $this->em->getRepository(User::class)->findBy([
                'campus' => $pilote->getCampus(),
                'role'   => Role::ETUDIANT,
            ]);
        }

        return $view->render($response, 'Étudiants/Page_Liste_Étudiant.html.twig', [
            'etudiants'    => $etudiants,
            'campus'       => $pilote?->getCampus(),
            'monCampus'    => true,
        ]);
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
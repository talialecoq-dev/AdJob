<?php

namespace App\Application\Controller;

use App\Domain\User;
use App\Domain\Role;
use App\Domain\Campus;
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



    public function listeUsers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view  = Twig::fromRequest($request);
        $users = $this->em->getRepository(User::class)->findAll();

        return $view->render($response, 'Users/Page_Liste_User.html.twig', [
            'users' => $users,
        ]);
    }



    public function inscriptionEtudiant(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $campusList = $this->em->getRepository(Campus::class)->findAll();

        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Etudiantsajout',
            'campusList' => $campusList,
        ]);
    }

    public function ajouterEtudiant(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $img  = $this->traiterUpload();

        $user = new User(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_BCRYPT),
            $data['adresse'] ?? null,
            $data['ville']   ?? null,
            $data['region']  ?? null,
            null,
            $img
        );

        $user->setRole(Role::ETUDIANT);
        $this->assignCampus($user, $data['campus'] ?? null);

        $this->em->persist($user);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function modifierEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $etudiant   = $this->em->find(User::class, (int) $args['id']);
        $campusList = $this->em->getRepository(Campus::class)->findAll();

        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Etudiantsmodifier',
            'etudiant'   => $etudiant,
            'campusList' => $campusList,
        ]);
    }

    public function updateEtudiant(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setEmail($data['email']);
            $user->setAdresse($data['adresse'] ?? null);
            $user->setVille($data['ville']     ?? null);
            $user->setRegion($data['region']   ?? null);

            $img = $this->traiterUpload();
            if ($img) {
                $user->setLogo($img);
            }

            $this->assignCampus($user, $data['campus'] ?? null);
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



    public function inscriptionPilote(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $campusList = $this->em->getRepository(Campus::class)->findAll();

        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Piloteajout',
            'campusList' => $campusList,
        ]);
    }

    public function ajouterPilote(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $img  = $this->traiterUpload();

        $user = new User(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_BCRYPT),
            null,
            null,
            null,
            $data['localisation'] ?? null,
            $img
        );

        $user->setRole(Role::PILOTE);
        $this->assignCampus($user, $data['campus'] ?? null);

        $this->em->persist($user);
        $this->em->flush();

        return $response->withHeader('Location', '/Liste-Utilisateurs')->withStatus(302);
    }

    public function modifierPilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view       = Twig::fromRequest($request);
        $pilote     = $this->em->find(User::class, (int) $args['id']);
        $campusList = $this->em->getRepository(Campus::class)->findAll();

        return $view->render($response, 'Entreprises/Page_Inscription_Entreprise.html.twig', [
            'type'       => 'Pilotemodifier',
            'pilote'     => $pilote,
            'campusList' => $campusList,
        ]);
    }

    public function updatePilote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->em->find(User::class, (int) $args['id']);

        if ($user) {
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setEmail($data['email']);
            $user->setLocalisation($data['localisation'] ?? null);

            $img = $this->traiterUpload();
            if ($img) {
                $user->setLogo($img);
            }

            $this->assignCampus($user, $data['campus'] ?? null);
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



    public function etudiantsDuCampus(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $view   = Twig::fromRequest($request);
        $user   = $request->getAttribute('user');

        $etudiants = [];
        $campus    = null;

        if ($user && $user->getCampus()) {
            $campus    = $user->getCampus();
            $etudiants = $this->em->getRepository(User::class)->findBy([
                'campus' => $campus,
                'role'   => Role::ETUDIANT,
            ]);
        }

        return $view->render($response, 'Étudiants/Page_Liste_Étudiant.html.twig', [
            'etudiants' => $etudiants,
            'campus'    => $campus,
            'monCampus' => true,
        ]);
    }



    private function assignCampus(User $user, ?string $campusName): void
    {
        if ($campusName) {
            $campus = $this->em->getRepository(Campus::class)->findOneBy(['nomVille' => $campusName]);
            if ($campus) {
                $user->setCampus($campus);
            }
        }
    }

    private function traiterUpload(): ?string
    {
        if (!isset($_FILES['image_profil']) || $_FILES['image_profil']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename   = uniqid() . '_' . basename($_FILES['image_profil']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image_profil']['tmp_name'], $targetPath)) {
            return $filename;
        }

        return null;
    }
}

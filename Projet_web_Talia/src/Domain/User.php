<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'users')]
class User
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private string $nom;

    #[Column(type: 'string', nullable: false)]
    private string $prenom;

    #[Column(type: 'string', nullable: false, unique: true)]
    private string $email;

    #[Column(name: 'mot_de_passe', type: 'string', nullable: false)]
    private string $motDePasse;

    #[Column(name: 'est_actif', type: 'boolean', nullable: false)]
    private bool $estActif = true;

    // Champs communs anciennement dans Etudiant
    #[Column(type: 'string', nullable: true)]
    private ?string $adresse = null;

    #[Column(type: 'string', nullable: true)]
    private ?string $ville = null;

    #[Column(type: 'string', nullable: true)]
    private ?string $region = null;

    // Champ anciennement dans Pilote
    #[Column(type: 'string', nullable: true)]
    private ?string $localisation = null;

    #[Column(type: 'string', nullable: true)]
    private ?string $logo = null;

    #[ManyToOne(targetEntity: Role::class)]
    private ?Role $role = null;

    #[ManyToOne(targetEntity: Campus::class)]
    private ?Campus $campus = null;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $motDePasse,
        ?string $adresse = null,
        ?string $ville = null,
        ?string $region = null,
        ?string $localisation = null,
        ?string $logo = null
    ) {
        $this->nom          = $nom;
        $this->prenom       = $prenom;
        $this->email        = $email;
        $this->motDePasse   = $motDePasse;
        $this->adresse      = $adresse;
        $this->ville        = $ville;
        $this->region       = $region;
        $this->localisation = $localisation;
        $this->logo         = $logo;
    }

    public function getId(): int { return $this->id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): void { $this->nom = $nom; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getMotDePasse(): string { return $this->motDePasse; }
    public function setMotDePasse(string $motDePasse): void { $this->motDePasse = $motDePasse; }

    public function isEstActif(): bool { return $this->estActif; }
    public function setEstActif(bool $estActif): void { $this->estActif = $estActif; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): void { $this->adresse = $adresse; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): void { $this->ville = $ville; }

    public function getRegion(): ?string { return $this->region; }
    public function setRegion(?string $region): void { $this->region = $region; }

    public function getLocalisation(): ?string { return $this->localisation; }
    public function setLocalisation(?string $localisation): void { $this->localisation = $localisation; }

    public function getLogo(): ?string { return $this->logo; }
    public function setLogo(?string $logo): void { $this->logo = $logo; }

    public function getRole(): ?Role { return $this->role; }
    public function setRole(?Role $role): void { $this->role = $role; }

    public function getCampus(): ?Campus { return $this->campus; }
    public function setCampus(?Campus $campus): void { $this->campus = $campus; }
}
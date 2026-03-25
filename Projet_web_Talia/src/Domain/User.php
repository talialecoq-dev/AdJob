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

    #[ManyToOne(targetEntity: Role::class)]
    private ?Role $role = null;

    #[ManyToOne(targetEntity: Campus::class)]
    private ?Campus $campus = null;

    public function __construct(string $nom, string $prenom, string $email, string $motDePasse)
    {
        $this->nom       = $nom;
        $this->prenom    = $prenom;
        $this->email     = $email;
        $this->motDePasse = $motDePasse;
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

    public function getRole(): ?Role { return $this->role; }
    public function setRole(?Role $role): void { $this->role = $role; }

    public function getCampus(): ?Campus { return $this->campus; }
    public function setCampus(?Campus $campus): void { $this->campus = $campus; }
}
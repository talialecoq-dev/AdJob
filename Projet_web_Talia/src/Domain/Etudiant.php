<?php

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'etudiants')]
class Etudiant
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private string $prenom;

    #[Column(type: 'string', nullable: false)]
    private string $nom;

    #[Column(type: 'string', nullable: false)]
    private string $email;

    #[Column(type: 'string', nullable: true)]
    private ?string $adresse;

    #[Column(type: 'string', nullable: true)]
    private ?string $ville;

    #[Column(type: 'string', nullable: true)]
    private ?string $region;

    #[Column(type: 'string', nullable: true)]
    private ?string $logo;

    #[Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $prenom,
        string $nom,
        string $email,
        ?string $adresse = null,
        ?string $ville = null,
        ?string $region = null,
        ?string $logo = null
    ) {
        $this->prenom    = $prenom;
        $this->nom       = $nom;
        $this->email     = $email;
        $this->adresse   = $adresse;
        $this->ville     = $ville;
        $this->region    = $region;
        $this->logo      = $logo;
        $this->createdAt = new DateTimeImmutable('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }
    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }
    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }
    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}

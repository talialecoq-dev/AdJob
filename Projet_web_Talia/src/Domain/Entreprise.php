<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity] 
#[Table(name: 'entreprises')] 
class Entreprise
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string')]
    private string $nom;

    #[Column(type: 'string')]
    private string $secteur;

    #[Column(type: 'string')]
    private string $email;

    #[Column(name: 'site_web', type: 'string')]
    private string $siteWeb;

    #[Column(type: 'string', nullable: true)]
    private ?string $image;


    public function __construct(string $nom, string $secteur, string $email, string $siteWeb, ?string $image = null)
    {
        $this->nom = $nom;
        $this->secteur = $secteur;
        $this->email = $email;
        $this->siteWeb = $siteWeb;
        $this->image = $image;
    }


    public function getId(): int { return $this->id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(?string $nom): void { $this->nom = $nom; }

    public function getSecteur(): string { return $this->secteur; }
    public function setSecteur(?string $secteur): void { $this->secteur = $secteur; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(?string $email): void { $this->email = $email; }

    public function getSiteWeb(): string { return $this->siteWeb; }
    public function setSiteWeb(?string $siteWeb): void { $this->siteWeb = $siteWeb; }

    public function getImage(): ?string { return $this->image; }
}
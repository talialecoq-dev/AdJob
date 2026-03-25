<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
#[Entity, Table(name: 'pilote')]
class Pilote {

#[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

#[Column(type: 'string', nullable: false)]
    private string $prenom;

#[Column(type: 'string', nullable: false)]
    private string $nom;

#[Column(type: 'string')]
    private string $email;

#[Column(type: 'string')]
    private string $campus;
    
#[Column(type: 'string')]
    private string $localisation;




 public function __construct(string $prenom,string $nom, string $email, string $campus, string $localisation)
    {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->campus = $campus;
        $this->localisation = $localisation;
    }
    public function getId(): int { return $this->id; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }

    public function getNom(): string { return $this->nom; }
    public function setNom(?string $nom): void { $this->nom = $nom; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(?string $email): void { $this->email = $email; }

    public function getCampus(): string { return $this->campus; }
    public function setCampus(?string $campus): void { $this->campus = $campus; }

    public function getLocalisation(): string { return $this->localisation; }
    public function setLocalisation(?string $localisation): void { $this->localisation = $localisation; }

}


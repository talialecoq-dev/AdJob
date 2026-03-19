<?php
namespace App\Domain\User;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Etudiants')]
class Etudiant
{
    #[Id, Column(type: 'int')]
    private int $id;

    #[Column(type: 'string')]
    private string $prenom;

    #[Column(type: 'string')]
    private string $nom;

    #[Column(type: 'string')]
    private string $email;

    #[Column(type: 'string')]
    private string $campus;

    #[Column(type: 'string')]
    private string $ville;

    public function getId(): int { return $this->id; }
    
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function getPrenom(): string { return $this->prenom; }

    public function setNom(string $nom): void { $this->nom = $nom; }
    public function getNom(): string { return $this->nom; }

    public function setEmail(string $email): void { $this->email = $email; }
    public function getEmail(): string { return $this->email; }

    public function setCampus(string $email): void { $this->email = $email; }
    public function getCampus(): string { return $this->email; }

    public function setVille(string $email): void { $this->email = $email; }
    public function getVille(): string { return $this->email; }
}
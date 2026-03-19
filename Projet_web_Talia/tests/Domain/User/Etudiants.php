<?php
namespace App\Domain\User;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'Etudiants')] // <--- Le nom exact de ta table dans phpMyAdmin
class Etudiant
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string')]
    private string $prenom;

    #[Column(type: 'string')]
    private string $nom;

    #[Column(type: 'string')]
    private string $email;

    // Getters et Setters (indispensables pour manipuler les données)
    public function getId(): int { return $this->id; }
    
    public function setPrenom(string $prenom): void { $this->prenom = $prenom; }
    public function getPrenom(): string { return $this->prenom; }

    public function setNom(string $nom): void { $this->nom = $nom; }
    public function getNom(): string { return $this->nom; }

    public function setEmail(string $email): void { $this->email = $email; }
    public function getEmail(): string { return $this->email; }
}
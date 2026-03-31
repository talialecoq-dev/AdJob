<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'campus')]
class Campus
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(name: 'nom_ville', type: 'string', nullable: false)]
    private string $nomVille;

    #[Column(name: 'est_actif', type: 'boolean', nullable: false)]
    private bool $estActif = true;

    public function __construct(string $nomVille)
    {
        $this->nomVille = $nomVille;
    }

    public function getId(): int { return $this->id; }

    public function getNomVille(): string { return $this->nomVille; }
    public function setNomVille(string $nomVille): void { $this->nomVille = $nomVille; }

    public function isEstActif(): bool { return $this->estActif; }
    public function setEstActif(bool $estActif): void { $this->estActif = $estActif; }

    public function __toString(): string
    {
        return $this->nomVille;
    }
}
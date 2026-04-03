<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'competences')]
class Competence
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(name: 'nom_competence', type: 'string', nullable: false)]
    private string $nomCompetence;

    public function __construct(string $nomCompetence)
    {
        $this->nomCompetence = $nomCompetence;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNomCompetence(): string
    {
        return $this->nomCompetence;
    }
    public function setNomCompetence(string $nomCompetence): void
    {
        $this->nomCompetence = $nomCompetence;
    }
}

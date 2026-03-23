<?php

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'offres')]
class Offre
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private string $titre;

    #[Column(type: 'string', nullable: false)]
    private string $entreprise;

    #[Column(type: 'string', nullable: true)]
    private ?string $logo;

    #[Column(type: 'string', nullable: false)]
    private string $duree;

    #[Column(type: 'string', nullable: false)]
    private string $remuneration;

    #[Column(type: 'string', nullable: false)]
    private string $domaine;

    #[Column(type: 'string', nullable: false)]
    private string $competences;

    #[Column(type: 'text', nullable: false)]
    private string $description;

    #[Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $titre,
        string $entreprise,
        string $duree,
        string $remuneration,
        string $domaine,
        string $competences,
        string $description,
        ?string $logo = null
    ) {
        $this->titre        = $titre;
        $this->entreprise   = $entreprise;
        $this->duree        = $duree;
        $this->remuneration = $remuneration;
        $this->domaine      = $domaine;
        $this->competences  = $competences;
        $this->description  = $description;
        $this->logo         = $logo;
        $this->createdAt    = new DateTimeImmutable('now');
    }

    public function getId(): int { return $this->id; }

    public function getTitre(): string { return $this->titre; }
    public function setTitre(string $titre): void { $this->titre = $titre; }

    public function getEntreprise(): string { return $this->entreprise; }
    public function setEntreprise(string $entreprise): void { $this->entreprise = $entreprise; }

    public function getLogo(): ?string { return $this->logo; }
    public function setLogo(?string $logo): void { $this->logo = $logo; }

    public function getDuree(): string { return $this->duree; }
    public function setDuree(string $duree): void { $this->duree = $duree; }

    public function getRemuneration(): string { return $this->remuneration; }
    public function setRemuneration(string $remuneration): void { $this->remuneration = $remuneration; }

    public function getDomaine(): string { return $this->domaine; }
    public function setDomaine(string $domaine): void { $this->domaine = $domaine; }

    public function getCompetences(): string{ return $this->competences;}

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }

    public function getCreatedAt(): DateTimeImmutable { return $this->createdAt; }
}
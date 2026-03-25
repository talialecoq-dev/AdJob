<?php

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'offres_stage')]
class OffreStage
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private string $titre;

    #[Column(type: 'text', nullable: true)]
    private ?string $description;

    #[Column(type: 'date_immutable', nullable: true)]
    private ?DateTimeImmutable $date;

    #[Column(type: 'string', nullable: true)]
    private ?string $remuneration;

    #[Column(type: 'string', nullable: true)]
    private ?string $duree;

    #[Column(type: 'string', nullable: true)]
    private ?string $statut;

    #[Column(name: 'etat', type: 'string', nullable: true)]
    private ?string $etat;

    
    #[ManyToOne(targetEntity: Entreprise::class)]
    #[JoinColumn(nullable: false)]
    private Entreprise $entreprise;

    
    #[ManyToOne(targetEntity: Campus::class)]
    private ?Campus $campus = null;

    
    #[ManyToMany(targetEntity: Competence::class)]
    #[JoinTable(name: 'offre_competences')]
    private Collection $competences;

    public function __construct(string $titre, Entreprise $entreprise)
    {
        $this->titre      = $titre;
        $this->entreprise = $entreprise;
        $this->competences = new ArrayCollection();
    }

    public function getId(): int { return $this->id; }

    public function getTitre(): string { return $this->titre; }
    public function setTitre(string $titre): void { $this->titre = $titre; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): void { $this->description = $description; }

    public function getDate(): ?DateTimeImmutable { return $this->date; }
    public function setDate(?DateTimeImmutable $date): void { $this->date = $date; }

    public function getRemuneration(): ?string { return $this->remuneration; }
    public function setRemuneration(?string $remuneration): void { $this->remuneration = $remuneration; }

    public function getDuree(): ?string { return $this->duree; }
    public function setDuree(?string $duree): void { $this->duree = $duree; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(?string $statut): void { $this->statut = $statut; }

    public function getEtat(): ?string { return $this->etat; }
    public function setEtat(?string $etat): void { $this->etat = $etat; }

    public function getEntreprise(): Entreprise { return $this->entreprise; }
    public function setEntreprise(Entreprise $entreprise): void { $this->entreprise = $entreprise; }

    public function getCampus(): ?Campus { return $this->campus; }
    public function setCampus(?Campus $campus): void { $this->campus = $campus; }

    public function getCompetences(): Collection { return $this->competences; }
    public function addCompetence(Competence $competence): void { $this->competences->add($competence); }
    public function removeCompetence(Competence $competence): void { $this->competences->removeElement($competence); }
}
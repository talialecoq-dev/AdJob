<?php

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'candidatures')]
class Candidature
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[ManyToOne(targetEntity: Offre::class)]
    #[JoinColumn(nullable: false)]
    private Offre $offre;

    #[Column(name: 'date_candidature', type: 'date_immutable', nullable: false)]
    private DateTimeImmutable $dateCandidature;

    #[Column(name: 'cv_file', type: 'string', nullable: true)]
    private ?string $cvFile;

    #[Column(name: 'lm_file', type: 'string', nullable: true)]
    private ?string $lmFile;

    #[Column(name: 'etat_candidature', type: 'string', nullable: true)]
    private ?string $etatCandidature;

    public function __construct(User $user, Offre $offre)
    {
        $this->user            = $user;
        $this->offre           = $offre;
        $this->dateCandidature = new DateTimeImmutable('now');
    }

    public function getId(): int { return $this->id; }

    public function getUser(): User { return $this->user; }
    public function getOffre(): Offre { return $this->offre; }

    public function getDateCandidature(): DateTimeImmutable { return $this->dateCandidature; }

    public function getCvFile(): ?string { return $this->cvFile; }
    public function setCvFile(?string $cvFile): void { $this->cvFile = $cvFile; }

    public function getLmFile(): ?string { return $this->lmFile; }
    public function setLmFile(?string $lmFile): void { $this->lmFile = $lmFile; }

    public function getEtatCandidature(): ?string { return $this->etatCandidature; }
    public function setEtatCandidature(?string $etat): void { $this->etatCandidature = $etat; }
}
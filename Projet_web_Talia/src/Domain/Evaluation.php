<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;


#[Entity, Table(name: 'evaluations')]
class Evaluation
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Offre::class)]
    #[ORM\JoinColumn(nullable: false)]  
    private Offre $offre;

    #[Column(type: 'integer', nullable: false)]
    private int $note;

    #[Column(type: 'text', nullable: true)]
    private ?string $commentaire;

    public function __construct(User $user, Offre $offre, int $note)
    {
        $this->user       = $user;
        $this->offre      = $offre;
        $this->note       = $note;
    }

    public function getId(): int { return $this->id; }

    public function getUser(): User { return $this->user; }

    public function getNote(): int { return $this->note; }
    public function setNote(int $note): void { $this->note = $note; }

    public function getOffre(): Offre { return $this->offre; }
    public function setOffre(Offre $offre): void { $this->offre = $offre; }

    public function setUser(User $user): void { $this->user = $user; }



    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): void { $this->commentaire = $commentaire; }
}
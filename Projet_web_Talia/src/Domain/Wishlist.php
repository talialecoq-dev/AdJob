<?php

namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'wishlists')]
class Wishlist
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[ManyToOne(targetEntity: Offre::class)]
    #[JoinColumn(nullable: false)]
    private Offre $offre;

    public function __construct(User $user, Offre $offre)
    {
        $this->user  = $user;
        $this->offre = $offre;
    }

    public function getId(): int { return $this->id; }

    public function getUser(): User { return $this->user; }
    public function getOffre(): Offre { return $this->offre; }
}
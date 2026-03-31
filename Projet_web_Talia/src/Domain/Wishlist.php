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
    #[JoinColumn(nullable: true)]
    private ?User $user = null; 

    #[ManyToOne(targetEntity: Offre::class)]
    #[JoinColumn(nullable: false)]
    private Offre $offre;

    public function __construct(Offre $offre)
    {
   
        $this->offre = $offre;
    }

    public function getId(): int { return $this->id; }
    public function getOffre(): Offre { return $this->offre; }
}
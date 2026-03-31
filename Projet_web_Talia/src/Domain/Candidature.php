<?php

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'candidatures')]
class Candidature
{

   #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[Column(type: 'string', nullable: false)]
    private string $nom;
    #[Column(type: 'string', nullable: false)]
    private string $prenom;
    #[Column(type: 'string', nullable: false)]
    private string $email;
    #[Column(type: 'string', nullable: false)]
    private string $titre;
    #[Column(type: 'string', nullable: false)]
    private string $remuneration;
    #[Column(type: 'string', nullable: false)]
    private string $duree;
    #[Column(type: 'string', nullable: false)]
    private string $domaine;
    #[Column(type: 'string', nullable: false)]
    private string $entreprise;
    #[Column(type: 'string', nullable: true)]
    private ?string $logo;
    #[Column(type: 'string', nullable: false)]
    private string $competences;
    #[Column(type: 'text', nullable: false)]
    private string $description;
    #[Column(type: 'string', nullable: false)]
    private string $statut;
    #[Column(type: 'string', nullable: false)]
    private string $color;
    #[Column(type: 'string', nullable: false)]
    private string $image;
   #[Column(name: 'description_card', type: 'string', nullable: false)]
    private string $desc;
    #[Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $titre,
        string $remuneration,
        string $duree,
        string $domaine,
        string $entreprise,
        ?string $logo,
        string $competences,
        string $description,
        User $user, 
        string $statut = 'En attente',
        string $color = 'warning',
        string $image = 'Image/Martin.png',
        string $desc = ''
        
    ) {
        $this->nom          = $nom;
        $this->prenom       = $prenom;
        $this->email        = $email;
        $this->titre        = $titre;
        $this->remuneration = $remuneration;
        $this->duree        = $duree;
        $this->domaine      = $domaine;
        $this->entreprise   = $entreprise;
        $this->logo         = $logo;
        $this->competences  = implode(',', array_map('trim', explode(',', $competences)));
        $this->description  = $description;
        $this->statut       = $statut;
        $this->color        = $color;
        $this->image        = $image;
        if (empty($desc)) {
            $desc = "Candidature soumise par {$prenom} {$nom} pour l'offre {$titre}";
        }
        $this->desc         = trim($desc);
        $this->createdAt    = new DateTimeImmutable();
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getTitre(): string { return $this->titre; }
    public function getRemuneration(): string { return $this->remuneration; }
    public function getDuree(): string { return $this->duree; }
    public function getDomaine(): string { return $this->domaine; }
    public function getEntreprise(): string { return $this->entreprise; }
    public function getLogo(): ?string { return $this->logo; }
    public function getCompetences(): array { return array_map('trim', explode(',', $this->competences)); }
    public function getDescription(): string { return $this->description; }
    public function getUser(): User {return $this->user;}
    public function getStatut(): string { return $this->statut; }
    public function getColor(): string { return $this->color; }
    public function getImage(): string { return $this->image; }
    public function getDesc(): string { return $this->desc; }
    public function getCreatedAt(): DateTimeImmutable { return $this->createdAt; }




}
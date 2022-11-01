<?php

namespace App\Entity;


use App\Entity\Traits\TimeStampableTrait;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[UniqueEntity('name', 'ce nom est déjà utilisé')]
#[ORM\HasLifecycleCallbacks]
class Recette
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 50,
        maxMessage: 'Le nom ne doit pas excéder 50 caracteres',
        minMessage: 'Le nom ne doit pas etre inférieur a 2 caractere'
    )]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Assert\Length(
        min: 1,
        max: 24,
        maxMessage: 'Le temps ne doit pas excéder 24 caracteres',
        minMessage: 'Le nom ne doit pas etre inférieur a 1 caractere'
    )]
    #[ORM\Column(length: 24, nullable: true)]
    private ?string $time = null;

    #[Assert\NotNull()]
    #[Assert\LessThan(50)]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nbrPersonnes = null;

    #[Assert\NotNull()]
    #[Assert\Range(
        min: 1,
        max: 6,
    )]
    #[ORM\Column(length: 6, nullable: true)]
    private ?string $difficulty = null;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\Positive()]
    #[Assert\LessThan(
        value: 1000,
        message: 'Le prix ne doit pas éxcéder 1000 €'
    )]
    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recettes')]
    private Collection $ingredient;

    #[ORM\Column(nullable: true)]
    private ?bool $isFavorite = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable;
        $this->ingredient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getNbrPersonnes(): ?string
    {
        return $this->nbrPersonnes;
    }

    public function setNbrPersonnes(?string $nbrPersonnes): self
    {
        $this->nbrPersonnes = $nbrPersonnes;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredient(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredient->removeElement($ingredient);

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(?bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }
}

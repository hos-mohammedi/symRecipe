<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampableTrait;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[UniqueEntity('name', 'ce nom est déjà utilisé')]
#[ORM\HasLifecycleCallbacks]
class Ingredient
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your first name must be at least {{ 2 }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ 50 }} characters',
    )]
    private ?string $name = null;

    #[Assert\NotNull()]
    #[ORM\Column]
    #[Assert\Range(
        min: 0,
        max: 200,
        notInRangeMessage: 'You must be between {{ min }}€ and {{ max }}€ tall to enter',
    )]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Recette::class, mappedBy: 'ingredient')]
    private Collection $recettes;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable;
        $this->recettes = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->addIngredient($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            $recette->removeIngredient($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}

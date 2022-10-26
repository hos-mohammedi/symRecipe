<?php

namespace App\Entity;

use App\Entity\Traits\TimeStampable;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    use TimeStampable;

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


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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
}

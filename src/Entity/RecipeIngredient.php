<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 4,
        minMessage: 'La quantité de votre ingredient doit faire au minimum {{ limit }} caractères',
        maxMessage: 'La quantité de votre ingredient ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    private ?Ingredient $ingredients = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    private ?Recipe $recipes = null;

    #[ORM\Column(length: 255)]
    private ?string $unit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIngredients(): ?Ingredient
    {
        return $this->ingredients;
    }

    public function setIngredients(?Ingredient $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getRecipes(): ?Recipe
    {
        return $this->recipes;
    }

    public function setRecipes(?Recipe $recipes): self
    {
        $this->recipes = $recipes;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}

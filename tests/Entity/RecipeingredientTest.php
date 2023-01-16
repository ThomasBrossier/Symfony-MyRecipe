<?php

namespace App\Tests\Entity;

use App\Entity\CategoryIngredient;
use App\Entity\Ingredient;
use App\Entity\Profile;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeingredientTest extends KernelTestCase
{
    public function getEntity(): RecipeIngredient
    {
        $user = new User();
        $user->setEmail('test@test.fr')
            ->setPassword('test')
            ->setRoles([]);
        $profile = new Profile();
        $profile->setLastname('test')
            ->setFirstname('test')
            ->setAvatar('test')
            ->setUser($user);
        $recipeStep = new RecipeStep();
        $recipeStep->setContent('test');
        $recipe = new Recipe();
        $recipe->addRecipeStep($recipeStep)
            ->setPicture('test')
            ->setSlug('test')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPerson(2)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setOrigin('test')
            ->setTitle('test')
            ->setAuthor($profile);
        $categoryIngredient = new CategoryIngredient();
        $categoryIngredient->setName('test')
            ->setPicture('test')
            ->setUpdatedAt(new \DateTimeImmutable());
        $ingredient = new Ingredient();
        $ingredient->setName('test')
            ->setSlug('test')
            ->setType('test')
            ->setPicture('test')
            ->setCategory($categoryIngredient);
        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setQuantity(2)
            ->setIngredient($ingredient)
            ->setUnit('cm')
            ->setRecipes($recipe);

        return $recipeIngredient;
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $recipeIngredients = $this->getEntity();
        $errors = $container->get('validator')->validate($recipeIngredients);

        $this->assertCount(0,$errors);
    }
    public function testInValidValues(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $recipeIngredients = $this->getEntity();
        $recipeIngredients->setQuantity('')
            ->setIngredient(null)
            ->setUnit('')
            ->setRecipes(null);

        $errors = $container->get('validator')->validate($recipeIngredients);

        $this->assertCount(4,$errors);
    }
}

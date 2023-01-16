<?php

namespace App\Tests\Entity;

use App\Entity\CategoryIngredient;
use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngredientTest extends KernelTestCase
{
    public function getEntity(): Ingredient
    {
        $ingredientCategory = new CategoryIngredient();
        $ingredientCategory->setName('test')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPicture('test');
        $ingredient = new Ingredient();
        $ingredient->setPicture('test')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setName('test')
            ->setType('test')
            ->setSlug('test')
            ->setCategory($ingredientCategory);
        return $ingredient;
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $categoryRecipe = $this->getEntity();
        $errors = $container->get('validator')->validate($categoryRecipe);

        $this->assertCount(0,$errors);
    }
    public function testInValidValues(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $categoryRecipe = $this->getEntity();
        $categoryRecipe->setName('')
            ->setType('')
            ->setSlug('');

        $errors = $container->get('validator')->validate($categoryRecipe);

        $this->assertCount(2,$errors);
    }
}

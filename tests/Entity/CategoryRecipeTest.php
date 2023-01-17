<?php

namespace App\Tests\Entity;

use App\Entity\CategoryRecipe;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRecipeTest extends KernelTestCase
{
    public function getEntity(): CategoryRecipe
    {
        $categoryRecipe = new CategoryRecipe();
        $categoryRecipe->setName('test')
            ->setPicture('test')
            ->setUpdatedAt(new \DateTimeImmutable());
        return $categoryRecipe;
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
            ->setPicture('');

        $errors = $container->get('validator')->validate($categoryRecipe);

        $this->assertCount(2,$errors);
    }
}

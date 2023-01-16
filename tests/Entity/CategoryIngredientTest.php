<?php

namespace App\Tests\Entity;

use App\Entity\CategoryIngredient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryIngredientTest extends KernelTestCase
{
    public function getEntity(): CategoryIngredient
    {
        $categoryIngredient = new CategoryIngredient();
        $categoryIngredient->setName('test')
            ->setPicture('test')
            ->setUpdatedAt(new \DateTimeImmutable());
        return $categoryIngredient;
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $categoryIngredient = $this->getEntity();
        $errors = $container->get('validator')->validate($categoryIngredient);

        $this->assertCount(0,$errors);
    }
    public function testInValidValues(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $categoryIngredient = $this->getEntity();
        $categoryIngredient->setName('');

        $errors = $container->get('validator')->validate($categoryIngredient);
        $this->assertCount(1,$errors);
    }
}

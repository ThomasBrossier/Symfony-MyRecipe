<?php

namespace App\Tests\Entity;

use App\Entity\Profile;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeStepTest extends KernelTestCase
{
    public function getEntity(): RecipeStep
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

        $recipe = new Recipe();
        $recipe->setPicture('test')
            ->setSlug('test')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPerson(2)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setOrigin('test')
            ->setTitle('test')
            ->setAuthor($profile);

        $recipeStep = new RecipeStep();
        $recipeStep->setContent('test')
                ->setRecipe($recipe);
        ;
        return $recipeStep;
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $recipeStep = $this->getEntity();
        $errors = $container->get('validator')->validate($recipeStep);

        $this->assertCount(0,$errors);
    }
    public function testInValidValues(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $recipeStep = $this->getEntity();
        $recipeStep->setContent('')
            ->setRecipe(null);

        $errors = $container->get('validator')->validate($recipeStep);

        $this->assertCount(2,$errors);
    }
}

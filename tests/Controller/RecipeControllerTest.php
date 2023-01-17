<?php

namespace App\Tests\Controller;

use App\Entity\CategoryRecipe;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\CategoryRecipeRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeControllerTest extends WebTestCase
{
    private KernelBrowser $client ;
    private  User $user;
    private Recipe $recipe;
    private CategoryRecipe $categoryRecipe;
    private string $path = 'recipe/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $categoryRecipeRepository = static::getContainer()->get(CategoryRecipeRepository::class);
        $recipeRepository = static::getContainer()->get(RecipeRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['email' => 'test@test.fr']);
        foreach ($recipeRepository->findAll() as $object){
            $recipeRepository->remove($object ,true);
        }
        foreach ($categoryRecipeRepository->findAll() as $object){
            $categoryRecipeRepository->remove($object ,true);
        }
        $categoryRecipe = new CategoryRecipe();
        $categoryRecipe->setName('TestCategoryRecipe')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPicture('TestCategoryRecipe');
        $categoryRecipeRepository->save($categoryRecipe, true);
        $this->categoryRecipe = $categoryRecipeRepository->findOneBy(['name'=> 'TestCategoryRecipe']);
        $recipe = new Recipe();
        $recipe->setUpdatedAt(new \DateTimeImmutable())
            ->setSlug('testRecipe')
            ->setTitle('testRecipe')
            ->setOrigin('testRecipe')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setAuthor($this->user->getProfile())
            ->setPerson(4)
            ->addCategory($this->categoryRecipe);
        $recipeRepository->save($recipe, true);
        $this->recipe = $recipeRepository->findOneBy(['title'=>'testRecipe']);


    }
    public function testNew(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', $this->path.'new');

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Créer une recette');
    }
    public function testCategoryView(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', $this->path.'category/'.$this->categoryRecipe->getId());

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recette de '.$this->categoryRecipe->getName());
    }
    public function testRecipeView(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', $this->path.$this->recipe->getId());

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recette : '.$this->recipe->getTitle());
    }
}

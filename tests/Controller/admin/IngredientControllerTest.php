<?php

namespace App\Tests\Controller\admin;

use App\Entity\CategoryIngredient;
use App\Entity\Ingredient;
use App\Entity\User;
use App\Repository\CategoryIngredientRepository;
use App\Repository\IngredientRepository;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IngredientControllerTest extends WebTestCase
{
    private KernelBrowser $client ;
    private IngredientRepository $repository;
    private Ingredient $ingredient;
    private ?CategoryIngredient $categoryIngredient = null;
    private ?CategoryIngredientRepository $categoryIngredientRepository = null;
    private User $user;
    private string $path = 'admin/ingredient/';

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['email' => 'test@test.fr']);
        $this->repository = static::getContainer()->get(IngredientRepository::class);
        $this->categoryIngredientRepository = static::getContainer()->get(CategoryIngredientRepository::class);
        $categoryIngredient = new CategoryIngredient();
        $categoryIngredient->setName('IngredientTest')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPicture('IngredientTest');
        $this->categoryIngredient = $categoryIngredient;
        $this->categoryIngredientRepository->save($categoryIngredient,true);
        $this->ingredient = new Ingredient();
        $this->ingredient->setName('testIngredientController')
        ->setPicture('test')
        ->setUpdatedAt(new \DateTimeImmutable())
        ->setSlug('test')
        ->setType('test')
        ->setCategory($categoryIngredient);
        $this->repository->save($this->ingredient, true);
        $this->ingredient = $this->repository->findOneBy(['name'=> 'testIngredientController' ]);

    }

    public function testIndex(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Liste des ingrédients');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

        public function testNew(): void
        {
            $this->client->loginUser($this->user);
            $originalNumObjectsInRepository = count($this->repository->findAll());
            $crawler = $this->client->request('GET', sprintf('%snew', $this->path));

            self::assertResponseStatusCodeSame(200);

            $form = $crawler->selectButton('Créer')->form();
            $form['ingredient[name]'] = 'TestingNewIngredient';
            $form['ingredient[type]'] = 'Solide';
            $form['ingredient[category]'] = $this->categoryIngredient->getId() ;
            $form['ingredient[imageFile][file]']->upload(__DIR__.'/../../Fixtures/test.jpg');
            $this->client->submit($form);
            self::assertResponseRedirects();
            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        }

        public function testShow(): void
        {
            $this->client->loginUser($this->user);
            $crawler =  $this->client->request('GET', sprintf('%s%s', $this->path, $this->ingredient->getId()));
            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('Ingredient : '.$this->ingredient->getName());

                   // Use assertions to check that the properties are properly displayed.
        }

        public function testEdit(): void
        {
            $this->client->loginUser($this->user);
            $crawler =  $this->client->request('GET', sprintf('%s%s/edit', $this->path, $this->ingredient->getId()));
            $form = $crawler->selectButton('Mise à jour')->form();
            $form['ingredient[name]'] = 'Something_New';
            $form['ingredient[type]'] = 'Liquide';
            $this->client->submit($form);


            $fixture = $this->repository->findOneBy(['name'=> 'Something_New']);
            self::assertSame('Something_New', $fixture->getName());
            self::assertSame('Liquide',$fixture->getType());
        }

        public function testRemove(): void
        {
            $this->client->loginUser($this->user);
            $originalNumObjectsInRepository = count($this->repository->findAll());
            $ingredient = new Ingredient();
            $ingredient->setName('My Title')
                    ->setType('Liquide')
                    ->setSlug('Liquide')
                    ->setPicture('My Title')
                    ->setUpdatedAt( new \DateTimeImmutable())
                    ->setCategory($this->categoryIngredient);
            $this->repository->save($ingredient, true);
            $ingredient = $this->repository->findOneBy(['name'=> 'My Title']);
            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
            $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, $ingredient->getId()));
            $form = $crawler->selectButton('Supprimer')->form();
            $this->client->submit($form);
            self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        }

        public function tearDown(): void
        {

            $ingredients =  $this->repository->findAll();
            foreach ($ingredients as $ingredient){
                $this->repository->remove($ingredient,true);
            }
            $categoryIngredient =  $this->categoryIngredientRepository->find($this->categoryIngredient->getId());
            $this->categoryIngredientRepository->remove($categoryIngredient , true);
        }
}

<?php

namespace App\Tests\Controller\admin;

use App\Entity\CategoryRecipe;
use App\Entity\User;
use App\Repository\CategoryRecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryRecipeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private User $user;
    private CategoryRecipeRepository $repository;
    private string $path = 'admin/category/recipe/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['email' => 'test@test.fr']);
        $this->repository = static::getContainer()->get('doctrine')->getRepository(CategoryRecipe::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }
    public function getEntity() :CategoryRecipe
    {
        $categoryRecipe  = new CategoryRecipe();
        $categoryRecipe->setName('Test')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPicture('test');
        return $categoryRecipe;
    }
    public function testIndex(): void
    {
        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Liste des Categories de recette');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

        public function testNew(): void
        {
            $this->client->loginUser($this->user);
            $originalNumObjectsInRepository = count($this->repository->findAll());

            $crawler = $this->client->request('GET', $this->path.'new');
            self::assertResponseStatusCodeSame(200);
            $form = $crawler->selectButton('Créer')->form();
            $form['category_recipe[name]'] = 'Test2';
            $form['category_recipe[imageFile][file]']->upload(__DIR__.'/../../Fixtures/test.jpg');
            $this->client->submit($form);

            self::assertResponseRedirects();
            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        }

            public function testShow(): void
            {
                $this->client->loginUser($this->user);

                $categoryRecipe = $this->getEntity();

                $this->repository->save($categoryRecipe, true);

                $this->client->request('GET', sprintf('%s%s', $this->path, $categoryRecipe->getId()));

                self::assertResponseStatusCodeSame(200);
                self::assertPageTitleContains('Catégorie de recette : '.$categoryRecipe->getName());

                // Use assertions to check that the properties are properly displayed.
            }

                public function testEdit(): void
                {
                    $this->client->loginUser($this->user);
                    $categoryRecipe = $this->getEntity();
                    $this->repository->save($categoryRecipe, true);
                    $crawler =  $this->client->request('GET', sprintf('%s%s/edit', $this->path, $categoryRecipe->getId()));
                    $form = $crawler->selectButton('Mettre à jour')->form();
                    $form['category_recipe[name]'] = 'Test3';
                    $this->client->submit($form);

                    self::assertResponseRedirects();

                    $fixture = $this->repository->findAll();
                    self::assertSame('Test3', $fixture[0]->getName());
                }

                    public function testRemove(): void
                    {
                        $this->client->loginUser($this->user);
                        $originalNumObjectsInRepository = count($this->repository->findAll());
                        $categoryRecipe = $this->getEntity();
                        $this->repository->save($categoryRecipe, true);
                        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
                        $crawler = $this->client->request('GET', sprintf('%s%s', $this->path, $categoryRecipe->getId()));
                        $form = $crawler->selectButton('Supprimer')->form();
                        $this->client->submit($form);
                        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
                        self::assertResponseRedirects();
                    }
                    protected function tearDown(): void
                    {
                        parent::tearDown();
                        foreach ($this->repository->findAll() as $object) {
                            $this->repository->remove($object, true);
                        }
                    }
}

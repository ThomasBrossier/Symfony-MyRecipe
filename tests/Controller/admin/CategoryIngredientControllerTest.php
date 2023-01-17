<?php

namespace App\Tests\Controller\admin;

use App\Entity\CategoryIngredient;
use App\Entity\User;
use App\Repository\CategoryIngredientRepository;
use App\Repository\UserRepository;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryIngredientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CategoryIngredientRepository $repository;
    private User $user;
    private string $path = "admin/category/ingredient/";

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['email' => 'test@test.fr']);
        $this->repository = static::getContainer()->get('doctrine')->getRepository(CategoryIngredient::class);
        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

        public function getEntity() :CategoryIngredient
        {
            $categoryIngredient = new CategoryIngredient();
            $categoryIngredient->setName('Test3')
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setPicture('test3');
            return $categoryIngredient;
        }
        public function testIndex(): void
        {
            $this->client->loginUser($this->user);
            $crawler = $this->client->request('GET', $this->path);
            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('Liste des Catégories d’ingrédient');

            // Use the $crawler to perform additional assertions e.g.
            // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
        }

        #[NoReturn]
        public function testNew(): void
        {
            $this->client->loginUser($this->user);
            $originalNumObjectsInRepository = count($this->repository->findAll());

            $crawler =  $this->client->request('GET', $this->path.'new');

            self::assertResponseStatusCodeSame(200);
            $form = $crawler->selectButton('Créer')->form();
            $form['category_ingredient[name]'] = 'TestingCatIngredientController';
            $form['category_ingredient[imageFile][file]']->upload(__DIR__.'/../../Fixtures/test.jpg');
            $this->client->submit($form);
            $response = $this->client->getResponse();
            self::assertResponseRedirects();
            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        }

            public function testShow(): void
            {
                $this->client->loginUser($this->user);
                $categoryIngredient = $this->getEntity();

                $this->repository->save($categoryIngredient, true);

                $this->client->request('GET', sprintf('%s%s', $this->path, $categoryIngredient->getId()));

                self::assertResponseStatusCodeSame(200);
                self::assertPageTitleContains('Catégorie d\'ingrédient : Test');

            }

            public function testEdit(): void
            {
                $this->client->loginUser($this->user);
                $fixture = $this->getEntity();
                $this->repository->save($fixture, true);

                $crawler = $this->client->request('GET', $this->path.$fixture->getId().'/edit');

                $form = $crawler->selectButton('Mettre à jour')->form();
                $form['category_ingredient[name]'] = 'TestingCatIngredientController2';
                $this->client->submit($form);
                self::assertResponseRedirects();
                $fixture = $this->repository->findAll();

                self::assertSame('TestingCatIngredientController2', $fixture[0]->getName());
            }

                public function testRemove(): void
                {
                    $this->client->loginUser($this->user);
                    $originalNumObjectsInRepository = count($this->repository->findAll());
                    $categoryIngredient = $this->getEntity();
                    $this->repository->save($categoryIngredient, true);
                    $crawler = $this->client->request('GET', $this->path.$categoryIngredient->getId().'/edit');
                    $form = $crawler->selectButton('Supprimer')->form();
                    $this->client->submit($form);
                    self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
                    self::assertResponseRedirects();
                }
}

<?php

namespace App\Test\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IngredientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private IngredientRepository $repository;
    private string $path = '/ingredient/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Ingredient::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ingredient index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'ingredient[name]' => 'Testing',
            'ingredient[slug]' => 'Testing',
            'ingredient[picture]' => 'Testing',
            'ingredient[category]' => 'Testing',
        ]);

        self::assertResponseRedirects('/ingredient/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ingredient();
        $fixture->setName('My Title');
        $fixture->setSlug('My Title');
        $fixture->setPicture('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ingredient');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ingredient();
        $fixture->setName('My Title');
        $fixture->setSlug('My Title');
        $fixture->setPicture('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ingredient[name]' => 'Something New',
            'ingredient[slug]' => 'Something New',
            'ingredient[picture]' => 'Something New',
            'ingredient[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ingredient/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Ingredient();
        $fixture->setName('My Title');
        $fixture->setSlug('My Title');
        $fixture->setPicture('My Title');
        $fixture->setCategory('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/ingredient/');
    }
}

<?php

namespace App\Tests\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    private KernelBrowser $client ;
    private  User $user;
    private string $path = 'admin/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['email' => 'test@test.fr']);

    }
    public function testIndex(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', $this->path);
        $this->client->followRedirect();

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tableau de bord');

    }
}

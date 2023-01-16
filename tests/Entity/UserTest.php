<?php

namespace App\Tests\Entity;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {
        $profile = new Profile();
        $profile->setUpdatedAt(new \DateTimeImmutable())
            ->setFirstname('test')
            ->setLastname('test')
            ->setAvatar('unknow_profile.svg');
        $user = new User();
        $user->setEmail('test@test2600.fr')
            ->setPassword('test')
            ->setProfile($profile)
            ->setRoles([]);
        return $user;
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(0,$errors);
    }
    public function testInValidValues(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        $user->setEmail('')
            ->setPassword('');

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(2,$errors);
    }
}

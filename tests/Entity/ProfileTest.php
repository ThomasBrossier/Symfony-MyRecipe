<?php

namespace App\Tests\Entity;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProfileTest extends KernelTestCase
{
    public function getEntity(): Profile
    {
        $user = new User();
        $user->setRoles([])
            ->setPassword('test')
            ->setEmail('test')
            ->setIsVerified(true);
        $profile = new Profile();
        $profile->setLastname('test')
            ->setFirstname('test')
            ->setAvatar('test')
            ->setUser($user);
        return $profile;
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $profile = $this->getEntity();
        $errors = $container->get('validator')->validate($profile);

        $this->assertCount(0,$errors);
    }
}

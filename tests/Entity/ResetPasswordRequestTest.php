<?php

namespace App\Tests\Entity;

use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResetPasswordRequestTest extends KernelTestCase
{
    public function getEntity(): ResetPasswordRequest
    {
        $user = new User();
        $user->setEmail('test@test.fr')
            ->setPassword('test')
            ->setRoles([]);
        return new ResetPasswordRequest($user, new \DateTime(),'test','test' );
    }

    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $resetPassword = $this->getEntity();
        $errors = $container->get('validator')->validate($resetPassword);

        $this->assertCount(0,$errors);
    }
}

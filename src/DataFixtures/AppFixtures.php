<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('lepharaon09@hotmail.fr')
            ->setPassword('$2y$13$wsyhZ0NXX99I7jN5ZgdFrusN3zIXHH4.QIOU1dI6a7REEE22iOMJm') // admin
            ->setRoles(['ROLE_ADMIN'])
            ;
        $manager->persist($user);

        $manager->flush();
    }
}

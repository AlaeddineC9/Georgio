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
            ->setPassword('$2y$13$I9OZAG8UvjwpsOqOOax2Fe/lsTe5Fyb3xsTAN2nDssykvKws1NEnO') // admin
            ->setRoles(['ROLE_ADMIN'])
            ;
        $manager->persist($user);

        $manager->flush();
    }
}

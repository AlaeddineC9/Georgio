<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('admin@admin.fr')
            ->setPassword('$2y$13$54kCFOc3Bx6I6B8LIXcq7uvoNQUvQTh5zVnyebTfZvZ7YQXvinWCm') // admin
            ->setRoles(['ROLE_ADMIN'])
            ;
        $manager->persist($user);

        $manager->flush();
    }
}

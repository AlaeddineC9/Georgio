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
        $user->setEmail('lepharaon09@hotmail.fr')
            ->setPassword('$2y$13$aaxaeLJCbsUcYgxwEhvZFOrG/xe.iKy8J/mnkkl/uZZljul13Nzp6') // admin
            ->setRoles(['ROLE_ADMIN'])
            ;
        $manager->persist($user);

        $manager->flush();
    }
}

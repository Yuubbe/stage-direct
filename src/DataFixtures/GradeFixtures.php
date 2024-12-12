<?php

namespace App\DataFixtures;

use App\Entity\Grade;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GradeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $Grade = new Grade();
        $Grade->setName('BTS SIO1');
        $manager->persist($Grade);
        $manager->flush();

        $Grade2 = new Grade();
        $Grade2->setName('BTS SIO2');
        $manager->persist($Grade2);
        $manager->flush();

        $Grade3 = new Grade();
        $Grade3->setName('BTS SAM1');
        $manager->persist($Grade3);
        $manager->flush();

        $Grade3_2 = new Grade();
        $Grade3_2->setName('BTS SAM2');
        $manager->persist($Grade3_2);
        $manager->flush();
        
        $Grade4 = new Grade();
        $Grade4->setName('BTS GPME1');
        $manager->persist($Grade4);
        $manager->flush();

        $Grade4_2 = new Grade();
        $Grade4_2->setName('BTS GPME2');
        $manager->persist($Grade4_2);
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $company = new Company();
        $company->setName('Sturno');
        $manager->persist($company);

        $company2 = new Company();
        $company2->setName('Amazon');
        $manager->persist($company2);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

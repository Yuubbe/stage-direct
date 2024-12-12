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
        $company->setAddress('5 rue de Condol');
        $company->setTel('01 02 03 04 05');
        $company->setMail('test@gmail.com');
        $company->setZipcode('50000');
        $manager->persist($company);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

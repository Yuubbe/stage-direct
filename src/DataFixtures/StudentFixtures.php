<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Student;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $student = new Student();
        $student->setFirstname('Fabrice');
        $student->setLastname('Pinard');
        $student->setAddress('9 rue du crâne de mathis');
        $student->setZipcode('10500');
        $student->setTown('Mont-Crâne');
        $student->addSchool($this->getReference(SchoolFixtures::NDLP_SCHOOL_REFERENCE));
        $manager->persist($student);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

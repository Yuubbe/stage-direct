<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\School;

class SchoolFixtures extends Fixture
{
    public const NDLP_SCHOOL_REFERENCE = 'school';
    
    public function load(ObjectManager $manager): void
    {
        $school = new School();
        $school->setName('Notre Dame de la Providence');
        $school->setAddress('9 Rue Chanoine Berenger');
        $school->setZipcode('50300');
        $school->setTown('Avranches');
        $manager->persist($school);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
        $this->addReference(self::NDLP_SCHOOL_REFERENCE, $school);
    }
}

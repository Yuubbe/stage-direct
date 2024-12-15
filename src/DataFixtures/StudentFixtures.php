<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Student;
use App\Entity\Grade;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer l'objet Grade correspondant à "BTS SIO2"
        $gradeRepository = $manager->getRepository(Grade::class);
        $grade = $gradeRepository->findOneBy(['name' => 'BTS SIO2']); // Récupère l'objet Grade basé sur le nom

        if ($grade === null) {
            throw new \Exception('Grade not found!');
        }

        // Création de l'étudiant
        $student = new Student();
        $student->setFirstname('Fabrice');
        $student->setLastname('Pinard');
        $student->setAddress('9 rue du crâne de mathis');
        $student->setZipcode('10500');
        $student->setTown('Mont-Crâne');
        $student->setGrade($grade); // Passer l'objet Grade

        // Ajouter l'école (assure-toi d'avoir la référence définie pour SchoolFixtures)
        $student->addSchool($this->getReference(SchoolFixtures::NDLP_SCHOOL_REFERENCE));
        
        $manager->persist($student);
        $manager->flush();
    }
}

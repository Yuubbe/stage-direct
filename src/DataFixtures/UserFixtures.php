<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


class UserFixtures extends Fixture
{
    

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        
    }
    
    public function load(ObjectManager $manager): void
    {
        
        

        $student = new User();
        $student->setEmail('laveille@ndlpavranches.fr');
        $student->setFirstname('Mathis');
        $student->setLastname('laveille');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $student,
            'UwU'
        );
        $student->setPassword($hashedPassword);
        $student->setRoles(['ROLE_STUDENT']);
        $manager->persist($student);

        $teacher = new User();
        $teacher->setEmail('bouquet@ndlpavranches.fr');
        $teacher->setFirstname('Laurent');
        $teacher->setLastname('Bouquet');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $teacher,
            'Legaté'
        );
        $teacher->setPassword($hashedPassword);
        $teacher->setRoles(['ROLE_TEACHER']);
        $manager->persist($teacher);

        $superadmin = new User();
        $superadmin->setEmail('debroise@ndlpavranches.fr');
        $superadmin->setFirstname('Jean-Michel');
        $superadmin->setLastname('Debroise');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $superadmin,
            'PDG'
        );
        $superadmin->setPassword($hashedPassword);
        $superadmin->setRoles(['ROLE_SUPERADMIN']);
        $manager->persist($superadmin);


      

        $manager->flush();
    }
}
  
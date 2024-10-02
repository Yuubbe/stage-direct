<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        
    }
    public function load(ObjectManager $manager): void
    {


        /* création du premier utilisateur */


        $student = new User();
        $student->setEmail('laveille@ndlpavranches.fr');
        $student->setFirstname('Mathis');
        $student->setLastname('laveille');
        $student->setPassword('m.laveille')
        $student->setRoles('[ROLE_STUDENT]');

        $teacher = new User();
        $teacher->setEmail('bouquet@ndlpavranches.fr');
        $teacher->setFirstname('Laurent');
        $teacher->setLastname('Bouquet');
        $teacher->setPassword('l.bouquet')
        $teacher->setRoles('[ROLE_TEACHER]');


        $superadmin = new User();
        $superadmin->setEmail('debroise@ndlpavranches.fr');
        $superadmin->setFirstname('Jean-Michel');
        $superadmin->setLastname('Debroise');
        $superadmin->setPassword('jm.debroise')
        $superadmin->setRoles('[ROLE_SUPERADMIN]');
        


        // $product = new Product();
        

        $manager->flush();

       
    }
}

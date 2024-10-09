<?php
namespace App\Tests;


use Amp\Http\Client\Request;
use App\Entity\Student;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class UserControllerTest extends WebTestCase
{
    public function testURL(): void
    {


        $client = static::createClient();
        $client->request('GET', '/user');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Student index');
        /*$client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('bouquet@ndlpavranches.fr');
        $client = loginUser($testUser);

        $client = request('GET', '/user');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextContains('h1', 'Bonjour Monsieur Bouquet');*/
        
    }
  
}
class StudentTest extends WebTestCase
{
    private Student $student1;


    
    public function seConnecter():void
    {


    }
    public function testName(): void
    {
        $student = new student();
        $student->setFirstName('Patrick');
        $this->assertSame('Patrick', $student->getFirstname());
    }
    public function testSomething(): void
    {
        $studentRespository = static::getContainer()->get(StudentRepository::class);
        $this->student1 = $studentRespository->findOneBy(
            [
                'firstname'=>'Fabrice',
                'lastname'=> 'Pinard',
            ]);
        dump($this->student1->getAddress());
        $this->assertEquals("9 rue du crâne de mathis", $this->student1->getAddress());
    }

	
}
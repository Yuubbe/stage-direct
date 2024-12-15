<?php

namespace App\Tests\Controller;

use App\Entity\Internship;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class InternshipControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/internship/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Internship::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Internship index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'internship[title]' => 'Testing',
            'internship[description]' => 'Testing',
            'internship[startDate]' => 'Testing',
            'internship[endDate]' => 'Testing',
            'internship[company]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Internship();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStartDate('My Title');
        $fixture->setEndDate('My Title');
        $fixture->setCompany('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Internship');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Internship();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setStartDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setCompany('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'internship[title]' => 'Something New',
            'internship[description]' => 'Something New',
            'internship[startDate]' => 'Something New',
            'internship[endDate]' => 'Something New',
            'internship[company]' => 'Something New',
        ]);

        self::assertResponseRedirects('/internship/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStartDate());
        self::assertSame('Something New', $fixture[0]->getEndDate());
        self::assertSame('Something New', $fixture[0]->getCompany());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Internship();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setStartDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setCompany('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/internship/');
        self::assertSame(0, $this->repository->count([]));
    }
}

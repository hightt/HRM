<?php

namespace App\Tests\Controller;

use App\Entity\WorkLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class WorkLogControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $workLogRepository;
    private string $path = '/time/sheet/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->workLogRepository = $this->manager->getRepository(WorkLog::class);

        foreach ($this->workLogRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('WorkLog index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'work_log[date]' => 'Testing',
            'work_log[hour_start]' => 'Testing',
            'work_log[hourEnd]' => 'Testing',
            'work_log[hoursNumber]' => 'Testing',
            'work_log[overtimeNumber]' => 'Testing',
            'work_log[isDayOff]' => 'Testing',
            'work_log[absenceSymbol]' => 'Testing',
            'work_log[employee]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->workLogRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new WorkLog();
        $fixture->setDate('My Title');
        $fixture->setHour_start('My Title');
        $fixture->setHourEnd('My Title');
        $fixture->setHoursNumber('My Title');
        $fixture->setOvertimeNumber('My Title');
        $fixture->setIsDayOff('My Title');
        $fixture->setAbsenceSymbol('My Title');
        $fixture->setEmployee('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('WorkLog');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new WorkLog();
        $fixture->setDate('Value');
        $fixture->setHour_start('Value');
        $fixture->setHourEnd('Value');
        $fixture->setHoursNumber('Value');
        $fixture->setOvertimeNumber('Value');
        $fixture->setIsDayOff('Value');
        $fixture->setAbsenceSymbol('Value');
        $fixture->setEmployee('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'work_log[date]' => 'Something New',
            'work_log[hour_start]' => 'Something New',
            'work_log[hourEnd]' => 'Something New',
            'work_log[hoursNumber]' => 'Something New',
            'work_log[overtimeNumber]' => 'Something New',
            'work_log[isDayOff]' => 'Something New',
            'work_log[absenceSymbol]' => 'Something New',
            'work_log[employee]' => 'Something New',
        ]);

        self::assertResponseRedirects('/time/sheet/');

        $fixture = $this->workLogRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getHour_start());
        self::assertSame('Something New', $fixture[0]->getHourEnd());
        self::assertSame('Something New', $fixture[0]->getHoursNumber());
        self::assertSame('Something New', $fixture[0]->getOvertimeNumber());
        self::assertSame('Something New', $fixture[0]->getIsDayOff());
        self::assertSame('Something New', $fixture[0]->getAbsenceSymbol());
        self::assertSame('Something New', $fixture[0]->getEmployee());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new WorkLog();
        $fixture->setDate('Value');
        $fixture->setHour_start('Value');
        $fixture->setHourEnd('Value');
        $fixture->setHoursNumber('Value');
        $fixture->setOvertimeNumber('Value');
        $fixture->setIsDayOff('Value');
        $fixture->setAbsenceSymbol('Value');
        $fixture->setEmployee('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/time/sheet/');
        self::assertSame(0, $this->workLogRepository->count([]));
    }
}

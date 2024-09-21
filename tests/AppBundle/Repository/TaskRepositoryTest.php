<?php

namespace Tests\App\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class TaskRepositoryTest extends KernelTestCase 
{
    /**
     * @var AbstractDatabaseTool
     */
    private $databaseTool;

    private $em;

    protected function setUp(): void
    {
        self::bootKernel();

        // Injecting the DatabaseToolCollection service
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testCount()
    {
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/TaskRepositoryTestFixtures.yaml'
        ]);

        $tasks = $this->em->getRepository(Task::class)->count([]);

        $this->assertEquals(15, $tasks);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
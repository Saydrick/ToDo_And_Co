<?php

namespace Tests\App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class UserRepositoryTest extends KernelTestCase 
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
            dirname(__DIR__) . '/Fixtures/UserRepositoryTestFixtures.yaml'
        ]);

        $users = $this->em->getRepository(User::class)->count([]);

        $this->assertEquals(11, $users);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
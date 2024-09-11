<?php

namespace Tests\App\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class UserTest extends KernelTestCase
{
    /**
     * @var AbstractDatabaseTool
     */
    private $databaseTool;

    public function getEntity(): User
    {
        return (new User())
            ->setUsername('User1')
            ->setEmail('user@todo.com')
            ->setPassword('1234')
            ->setRoles(['ROLE_USER']);
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($user);

        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankUsernameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    public function testInvalidBlankRolesEntity()
    {
        $this->assertHasErrors($this->getEntity()->setRoles([]), 1);
    }

    public function testInvalidBlankEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testInvalidFormatEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('user'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('user.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('user@todo'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('@todo.com'), 1);
    }

    public function testInvalidUsedEmail()
    {
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserRepositoryTestFixtures.yaml'
        ]);
        $this->assertHasErrors($this->getEntity()->setEmail('admin@todo.com'), 1);
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }

}

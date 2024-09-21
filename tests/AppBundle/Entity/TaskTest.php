<?php

namespace Tests\App\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle('Tache 1')
            ->setContent('Description de la tache 1')
            ->setCreatedAt(new \DateTime())
            ->setUser(new User());
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($task);

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

    public function testInvalidBlankTitleEntity()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testInvalidBlankContentEntity()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Generates admin user
        $admin_user = new User();
        $admin_user->setEmail('admin@todo.com');
        $admin_user->setUsername('admin');
        $admin_user->setPassword($this->userPasswordHasher->hashPassword($admin_user, 'password'));
        $admin_user->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin_user);

        // Generates visitor user
        $visitor_user = new User();
        $visitor_user->setEmail('user@todo.com');
        $visitor_user->setUsername('user');
        $visitor_user->setPassword($this->userPasswordHasher->hashPassword($visitor_user, 'password'));
        $visitor_user->setRoles(['ROLE_USER']);

        $manager->persist($visitor_user);

        // Generates anonyme user
        $anonyme_user = new User();
        $anonyme_user->setEmail('anonyme@todo.com');
        $anonyme_user->setUsername('anonyme');
        $anonyme_user->setPassword($this->userPasswordHasher->hashPassword($anonyme_user, 'password'));
        $anonyme_user->setRoles(['ROLE_USER']);

        $manager->persist($anonyme_user);

        
        // Generates fictitious tasks
        for ($i = 0; $i < 4; $i++) {
            $task = new Task();
            $number_task = $i + 1;

            $task->setTitle('Tâche n°' . $number_task);
            $task->setContent('TODO : C\'est la tâche n°' . $number_task);
            $task->setUser($anonyme_user);

            $manager->persist($task);
        }

        $manager->flush();
    }
}

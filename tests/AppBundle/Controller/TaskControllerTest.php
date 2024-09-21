<?php

namespace Tests\App\Controller;

// use Tests\App\Traits\LoginTrait;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class TaskControllerTest extends WebTestCase
{    
    private $client;
    private $databaseTool;
    private $em;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/TaskRepositoryTestFixtures.yaml'
        ]);

        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testFixturesLoad(): void
    {
        $task = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task1']);
        $this->assertNotNull($task);
    }

    protected function login(string $user, string $password): void
    {
        $this->databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserRepositoryTestFixtures.yaml'
        ]);
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $user,
            '_password' => $password
        ]);
        $this->client->submit($form);
    }

    protected function assertRedirectsToLoginForUnauthorizedAccess(string $route, array $parameters = []): void
    {
        $url = $this->client->getContainer()->get('router')->generate($route, $parameters);
        $this->client->request('GET', $url);
        $this->assertResponseRedirects('/login');
    }


    public function testTaskListPageIsRestricted()
    {
        $this->assertRedirectsToLoginForUnauthorizedAccess('task_list');
    }

    public function testTaskListPageWithSufficientRole(): void
    {
        $this->login('user1', '1234');        
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTaskListPageWithAdminRole(): void
    {
        $this->login('admin', '1234');        
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCompletedTaskListPageWithSufficientRole(): void
    {
        $this->login('user1', '1234');        
        $this->client->request('GET', '/tasks/completed');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCompletedTaskListPageWithAdminRole(): void
    {
        $this->login('admin', '1234');        
        $this->client->request('GET', '/tasks/completed');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateAction(): void
    {
        $this->login('user1', '1234');
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, ['task[title]' => 'Titre test', 'task[content]' => 'Contenu de test']);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'La tâche a été bien été ajoutée.');
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

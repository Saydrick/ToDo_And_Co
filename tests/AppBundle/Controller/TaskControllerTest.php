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
    /**
     *  listAction : liste des taches du user
     *      -> verifie si l'utilisateur est connecté
     *          -> OUI : OK
     *          -> NON : redirect /login
     *      -> affiche la liste des taches
     * 
     *  listCompletedTask : liste des taches terminées du user
     *      -> verifie si l'utilisateur est connecté
     *          -> OUI : OK
     *          -> NON : redirect /login
     *      -> affiche la liste des taches terminées
     * 
     *  createAction : ajoute une tache
     *      -> verifie si l'utilisateur est connecté
     *          -> OUI : OK
     *          -> NON : redirect /login
     *      -> affiche le formulaire
     *      -> mauvaise saisie = error
     *      -> bonne saisie = redirect + success + (save bdd ?)
     * 
     *  editAction : modifie une tache
     *      -> affiche le formulaire
     *      -> mauvaise saisie = error
     *      -> bonne saisie = redirect + success + (update bdd ?)
     * 
     *  toggleTaskAction : valide une tache
     *      -> valide la tache : redirect + success
     * 
     *  deleteTaskAction : supprime une tache
     *      -> supprime la tache : redirect + success
     * 
     */
    
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

    /*
    public function testEditAction(): void
    {
        $taskID = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task1'])->getId();
        $this->login('user1', '1234');
        // dd($taskID);
        $crawler = $this->client->request('GET', 'tasks/' . $taskID . '/edit');
        // dd($crawler);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // $form = $crawler->selectButton('Modifier')->form();
        // $this->client->submit($form, ['task[title]' => 'Titre modifié', 'task[content]' => 'Contenu modifié']);
        // $this->client->followRedirect();
        // $this->assertSelectorTextContains('.alert-success', 'La tâche a bien été modifiée.');
    }
    

    public function testToggleTaskAction(): void
    {
        $task = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task1']);
        $this->login('admin', '1234');
        $crawler = $this->client->request('GET', 'task');
        $crawler->selectButton('Marquer comme faite');
        $this->client->request('GET', 'tasks/' . $task->getId() . '/toggle');
        // dd($task);
        // dd($task->getId());
        // dd($this->client->getResponse()->getContent());
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'La tâche Titre modifié a bien été marquée comme faite.');
    }
*/
    public function testDeleteTaskAction(): void
    {
        $taskID = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task1'])->getId();
        $this->login('user1', '1234');
        $crawler = $this->client->request('GET', 'task');
        $crawler->selectButton('Supprimer');
        $this->client->request('GET', 'tasks/' . $taskID . '/delete');
        $task = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task1']);
        $this->assertEquals(null, $task);
        // $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // $this->assertSelectorTextContains('.alert-success', 'La tâche a bien été supprimée.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

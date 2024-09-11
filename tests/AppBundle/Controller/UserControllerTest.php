<?php

namespace Tests\App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class UserControllerTest extends WebTestCase
{
    /**
     *  listAction : liste des users (admin)
     *      -> redirige vers la page si admin (admin = success 200 | user = error 403)
     *      -> récupère la liste des users
     *      -> Affiche le template
     * 
     *  createAction : add user
     *      -> affiche le formulaire
     *      -> mauvaise saisie = error
     *      -> bonne saisie = redirect + success + (save bdd ?)
     * 
     *  editAction : modification d'un user
     *      -> affiche le formulaire
     *      -> mauvaise saisie = error
     *      -> bonne saisie = redirect + success + (update bdd ?)
     * 
     */

    private $client;
    private $databaseTool;
    private $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
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

    public function assertForbiddenResponseForNonAdminUser(string $route, array $parameters = []): void
    {
        $this->login('user1', '1234');
        $url = $this->client->getContainer()->get('router')->generate($route, $parameters);
        $this->client->request('GET', $url);
        // dd($url);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }


    public function testAdminPageAccessWithoutAuthentication(): void
    {
        $this->assertRedirectsToLoginForUnauthorizedAccess('user_list');
        $this->assertRedirectsToLoginForUnauthorizedAccess('user_create');

        $user = $this->em->getRepository(User::class)->findOneByUsername('user1');
        $userId = $user->getId();
        $this->assertRedirectsToLoginForUnauthorizedAccess('user_edit', ['id' => $userId]);
    }

    public function testAdminPageAccessWithoutAdminRole(): void
    {
        $this->assertForbiddenResponseForNonAdminUser('user_list');
        $this->assertForbiddenResponseForNonAdminUser('user_create');

        $user = $this->em->getRepository(User::class)->findOneByUsername('user1');
        $userId = $user->getId();
        // dd($userId);
        $this->assertForbiddenResponseForNonAdminUser('user_edit', ['id' => $userId]);
    }

    public function testUserListPageWithSufficientRole()
    {
        $this->login('admin', '1234');        
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }    
}

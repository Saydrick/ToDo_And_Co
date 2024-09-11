<?php

namespace Tests\App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
// use Tests\App\Traits\LoginTrait;

class SecurityControllerTest extends WebTestCase
{    
    // use LoginTrait;

    private $client;
    private $databaseTool;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }
    
    public function login($user, $password): void
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


    public function testDisplayLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testLoginWithBadCredentials()
    {
        // $this->login('user1', 'badpassword', $this->client, $this->databaseTool);
        $this->login('user1', 'badpassword');
        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginWithGoodCredentials()
    {
        $this->login('user1', '1234');
        $this->client->followRedirect();
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLogout()
    {
        $this->login('user1', '1234');
        $this->client->request('GET', '/logout');
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

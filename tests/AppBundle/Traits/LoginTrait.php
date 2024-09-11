<?php

namespace Tests\App\Traits;

trait LoginTrait
{
    protected function login($username, $password, $client, $databaseTool): void
    {
        $databaseTool->loadAliceFixture([
            dirname(__DIR__) . '/Fixtures/UserRepositoryTestFixtures.yaml'
        ]);

        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $username,
            '_password' => $password,
        ]);

        $client->submit($form);
    }
}
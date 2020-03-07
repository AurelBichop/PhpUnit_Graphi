<?php

namespace App\Tests\Controller;


use App\Entity\User;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PageControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testHelloPage(){
        $client = static::createClient();
        $client->request('GET','/hello');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1HelloPage(){
        $client = static::createClient();
        $client->request('GET','/hello');

        $this->assertSelectorTextContains('h1','Bienvenue sur mon site');
    }

    public function testAuthPageIsRestricted(){
        $client = static::createClient();
        $client->request('GET','/auth');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testRedirectToLogin(){
        $client = static::createClient();
        $client->request('GET','/auth');

        $this->assertResponseRedirects('/login');
    }

    public function testLetAuthenticatedUserAccessAuth(){
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__.'/users.yaml']);

        $this->login($client, $users['user_user']);

        $client->request('GET','/auth');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }



}
<?php


namespace App\Tests\Controller;


use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testDisplayLogin(){
        $client = static::createClient();
        $client->request('GET','/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1','Se connecter');
        $this->assertSelectorNotExists('alert alert-danger');
    }

    public function testLoginWithBadCredentials(){
        $client = static::createClient();
        $crawler = $client->request('GET','/login');

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'john@doe.fr',
            'password' => 'fakepassword'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/login');

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessFullLogin(){
        $this->loadFixtureFiles([__DIR__.'/users.yaml']);
        $client = static::createClient();
       /**Une solution
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'john@doe.fr',
            'password' => '000000'
        ]);
        $client->submit($form);
        **/
        //Deuxieme solution
        $csrfToken = $client->getContainer()->get("security.csrf.token_manager")->getToken('authenticate');
        $client->request('POST', '/login',[
            '_csrf_token'=>$csrfToken,
            'email' => 'john@doe.fr',
            'password' => '000000'
        ]);

        $this->assertResponseRedirects('/auth');
    }


}
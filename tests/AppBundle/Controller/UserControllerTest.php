<?php


namespace App\Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /*public function testAccessIndex()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/user/'
        );

        $this->assertSame(true,$client->getResponse()->isRedirect('/user/login'));
    }*/

    public function testAccessParametre()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/user/parametre'
        );

        $this->assertSame(true,$client->getResponse()->isRedirect('/user/login'));
    }

    /*public function testLogin()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/user/login'
        );

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('submit')->form();
        $form['username']='lionelsan';
        $form['password']="azerty789456123";

        $crawler = $client->submit($form);
        echo "here";

    }*/
}
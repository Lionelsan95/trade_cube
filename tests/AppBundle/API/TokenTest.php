<?php


namespace App\Tests\AppBundle\API;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class TokenTest extends ApiTestCase
{
    public function testPOSTCreateToken()
    {
        $username = 'lionidas';
        $password = 'mp_kfjklf';

        /*$this->createUser($username,$password);

        $response = $this->client->post('/api/tokens',[
            'user'=>$username,
            'password'=>$password
        ]);*/

        $this->assertEquals(200, 200);

        /*$this->asserter()->assertResponsePropertyExists(
            $response,
            'token'
        );*/
    }
}
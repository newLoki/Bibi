<?php
namespace Bibi\Tests\Application;
use Silex\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;


class UsersControllerTest extends \Tests\ApplicationTestCase
{
    /**
     * @todo test offset for user list (index)
     */

    public function testUserList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/users/', array(
            "offset" => 0,
            "rows"   => 10
        ));

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertTrue(is_array(json_decode($response->getContent())));
    }

    public function testUserListWithSingleUser()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/users/', array(
            "offset" => 0,
            "rows"   => 1
        ));

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent());
        $this->assertTrue(is_array($data));
        $this->assertCount(1, $data);

        $user = $data[0];

        $this->assertInstanceOf('\stdClass', $user);
        $this->assertObjectHasAttribute('name', $user);
        $this->assertObjectHasAttribute('surname', $user);
        $this->assertObjectHasAttribute('lastname', $user);
        $this->assertObjectHasAttribute('email', $user);
        $this->assertObjectHasAttribute('birthdate', $user);
    }

    public function testExistingUserResponse()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/users/jon');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent());

        $user = new \stdClass();
        $user->name = 'jon';
        $user->surname = 'john';
        $user->lastname = 'doe';
        $user->email = 'john.doe@example.com';
        $user->birthdate = "1990-01-01";

        $expectedData = new \stdClass();
        $expectedData->users = array($user);
        $this->assertEquals($expectedData, $data);
    }

    public function testNonExistingUser()
    {
        $client = $this->createClient();
        $crwaler = $client->request('GET', '/users/non-existing-user');

        $response = $client->getResponse();

        $this->assertEquals('404', $response->getStatusCode());

        $expectedData = new \stdClass();
        $expectedData->messageId = 'user.notfound';

        $this->assertEquals($expectedData, json_decode($response->getContent()));
    }

    public function testCreationOfGoodUser()
    {
        /*if(APPLICATION_ENV != 'citest') {
            $this->markTestSkipped('Skipp integration test, testdata are only clean on ci');
        }*/

        $user = new \stdClass();
        $user->name = 'foo';
        $user->surname = 'john';
        $user->lastname = 'doe';
        $user->email = 'john.doe@example.com';
        $user->birthdate = "1990-01-01";

        $client = $this->createClient();
        $crwaler = $client->request('POST', '/users/',
            array("data" => json_encode($user))
        );
        $response = $client->getResponse();
        /** @var $headers Symfony\Component\HttpFoundation\ResponseHeaderBag */
        $headers = $response->headers;

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertContains('/users/foo', $headers->get("Location"));
    }

    public function testCreationOfExistingUser()
    {
        //should return user already exists as message and a location header with
        //the existsing user
        //also should return 303 (see other)
        $this->markTestIncomplete();
    }
}
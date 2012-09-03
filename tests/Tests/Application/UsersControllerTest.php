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
        $crawler = $client->request('GET', '/users/1');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent());

        $user = new \stdClass();
        $user->id = 1;
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
        $crwaler = $client->request('GET', '/users/666');

        $response = $client->getResponse();

        $this->assertEquals('404', $response->getStatusCode());

        $expectedData = new \stdClass();
        $expectedData->messageId = 'user.notfound';

        $this->assertEquals($expectedData, json_decode($response->getContent()));
    }

    public function testCreationOfGoodUser()
    {
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
        $location = $headers->get("Location");
        $this->assertContains('/users/', $location);

        //remove created user
        $client = $this->createClient();
        $crwaler = $client->request('DELETE', $location);
    }

    public function testCreationOfBadUser()
    {
        $this->markTestIncomplete();
    }

    public function testEmptyCreateUser()
    {
        $this->markTestIncomplete();
    }

    public function testCreationOfExistingUser()
    {
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
        $userLocation = $client->getResponse()->headers->get("Location");

        //make the second request with the same data
        $client = $this->createClient();
        $crwaler = $client->request('POST', '/users/',
            array("data" => json_encode($user))
        );
        $response = $client->getResponse();
        $location = $response->headers->get("Location");
        $this->assertEquals(303, $response->getStatusCode());
        $this->assertContains($userLocation, $location);
        //check content user.exists
        $result = json_decode($response->getContent());
        $this->assertEquals('user.exists', $result->messageId);

        //remove created user
        $client = $this->createClient();
        $crwaler = $client->request('DELETE', $location);
    }

    public function testDeletionOfExistingUser()
    {
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
        $userLocation = $client->getResponse()->headers->get("Location");

        //remove created user
        $client = $this->createClient();
        $crwaler = $client->request('DELETE', $userLocation);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $result = json_decode($response->getContent());
        $this->assertEquals('user.removed', $result->messageId);
    }

    public function testDeletionOfNonExistingUser()
    {
        //remove a not existing user
        $client = $this->createClient();
        $crwaler = $client->request('DELETE', '/users/666');
        $response = $client->getResponse();
        //resource has gone, give 410 as status means gone
        $this->assertEquals(410, $response->getStatusCode());
        $result = json_decode($response->getContent());
        $this->assertEquals('user.notexists', $result->messageId);
    }
}
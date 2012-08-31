<?php
namespace Bibi\Tests\Application;
use Silex\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @todo this is more like an integration test, maybee it is sensefull to move it into a new
 * directoy/test scenario who is called integration test
 */
class UsersControllerTest extends \Tests\ApplicationTestCase
{
    public function testExistingUserResponse()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/users/jon', array(), array(), array());

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
}
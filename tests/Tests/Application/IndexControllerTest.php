<?php
namespace Bibi\Tests\Application;
use Silex\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @todo this is more like an integration test, maybee it is sensefull to move it into a new
 * directoy/test scenario who is called integration test
 */
class IndexControllerTest extends \Tests\ApplicationTestCase
{
    public function testIndexResponse()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/', array(), array(), array());

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent());
        $expectedData = new \stdClass();
        $expectedData->name = "foo";

        $this->assertEquals($expectedData, $data);
    }
}
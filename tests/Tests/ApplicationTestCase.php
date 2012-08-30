<?php
namespace Tests;
use Silex\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

/**
 * @todo this is more like an integration test, maybee it is sensefull to move it into a new
 * directoy/test scenario who is called integration test
 */
class ApplicationTestCase extends BaseWebTestCase
{

    public function createApplication()
    {
        // load Silex
        //dont refactor to use require_once, this will break all -.-
        $app = require realpath(__DIR__.'/../../app/app.php');

        return $app;
    }
}
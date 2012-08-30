<?php
namespace Bibi\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class UserControllerProvider implements \Silex\ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();

        $app->get('/users/{id}', function($id) use ($app)
        {
            $data = new \stdClass();
            $data->users = array();
            return $app->json($data); //@todo return a specific user, or all if no id given
        })->bind('user.id');

        /*
         * @todo create user
         *       delete user
         *       update user
         *       Application tests
         */

        return $controllers;
    }
}



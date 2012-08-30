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

        $app->get('/users/index', function() use ($app){
            return ""; //@todo return a list off all users as json
        })->bind('user.index');

        $app->get('/users/{id}', function($id) use ($app) {
            return ""; //@todo return a specific user
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



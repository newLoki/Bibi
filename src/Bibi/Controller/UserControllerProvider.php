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

        $app->get('/users/', function() use ($app) {
            /** @var $request \Symfony\Component\HttpFoundation\Request */
            $request = $app['request'];

            $rows = (int) $request->get('rows', 10);
            $offset = (int) $request->get('offset', 0);

            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $app['db.orm.em'];
            $query = $em->createQuery(
                'SELECT u.surname, u.lastname, u.email, u.birthdate, u.name
                FROM Bibi\Entity\User u
             ');
            $query->setMaxResults($rows);
            $query->setFirstResult($offset);

            $result = $query->getResult();


            return $app->json($result);
        })->bind('users.index');

        $app->get('/users/{id}', function($id) use ($app)
        {

            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $app['db.orm.em'];
            $query = $em->createQuery(
                'SELECT u.surname, u.lastname, u.email, u.birthdate, u.name
                    FROM Bibi\Entity\User u
                    WHERE u.name = ?1');
            $query->setParameter('1', $id);
            $query->setMaxResults(1);
            $result = $query->getResult();

            $data = new \stdClass();

            if(!empty($result) && !empty($result[0])) {
                $data->users = array();
                $user = new \stdClass();
                foreach($result[0] as $key => $value) {
                    $user->{$key} = $value;
                }

                $data->users[] = $user;

                return $app->json($data);
            } else {
                $data->messageId = "user.notfound";

                return $app->json($data, 404);
            }


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



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
            $result = $query->getSingleResult();

            $data = new \stdClass();
            $data->users = array();

            if(!empty($result)) {
                $user = new \stdClass();
                foreach($result as $key => $value) {
                    $user->{$key} = $value;
                }

                $data->users[] = $user;
            }

            return $app->json($data);
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



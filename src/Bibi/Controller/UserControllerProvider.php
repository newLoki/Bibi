<?php
namespace Bibi\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Validator;

class UserControllerProvider implements \Silex\ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection($app['route_factory']);

        $app->get('/users/', function() use ($app) {
            /** @var $request \Symfony\Component\HttpFoundation\Request */
            $request = $app['request'];

            $rows = (int)$request->get('rows', 10);
            $offset = (int)$request->get('offset', 0);

            /** @var $em \Doctrine\ORM\EntityManager */
            //@todo refactor to use repo
            $em = $app['doctrine_orm.em'];
            $query = $em->createQuery(
                'SELECT u.surname, u.lastname, u.email, u.birthdate, u.name
                FROM Bibi\Entity\User u
             ');
            $query->setMaxResults($rows);
            $query->setFirstResult($offset);

            $result = $query->getResult();


            return $app->json($result);
        })->bind('users.index');

        $app->get('/users/{id}', function($id) use ($app) {

            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $app['doctrine_orm.em'];
            $user = $em->getRepository("Bibi\Entity\User");
            $result = $user->findOneById($id);

            $data = new \stdClass();
            $status = 500;

            if (!empty($result)) {
                $data->users = array($result->getSimpleObject());
                $status = 200;
            } else {
                $data->messageId = "user.notfound";
                $status = 404;

            }

            return $app->json($data, $status);
        })->bind('user.id')
        ->convert('id', function ($id) { return (int) $id; });

        $app->post('/users/', function() use ($app) {
            /** @var $request \Symfony\Component\HttpFoundation\Request */
            $request = $app['request'];

            $requestData = json_decode($request->get('data', ""));
            $data = new \stdClass();
            $status = 500;
            $headers = array();

            if (!empty($requestData)) {
                /** @var $validator Symfony\Component\Validator\Validator*/
                $validator = $app['validator'];
                $errors = $validator->validate($requestData);

                if (count($errors) > 0) {
                    $data->messageId = "user.datainvalid";
                    $status = 404;
                } else {
                    /** @var $em \Doctrine\ORM\EntityManager */
                    $em = $app['doctrine_orm.em'];
                    /** @var $userRepo \Bibi\Repo\UserRepo */
                    $userRepo = $em->getRepository("Bibi\Entity\User");
                    /** @var $result \Bibi\Entity\User */
                    $result = $userRepo->findOneByName($requestData->name);

                    if (empty($result)) {
                        $user = new \Bibi\Entity\User();
                        foreach (get_object_vars($requestData) as $attr => $value) {
                            $methodName = "set" . ucfirst($attr);
                            call_user_func_array(
                                array($user, $methodName),
                                array($value)
                            );

                        }

                        $em->persist($user);
                        $em->flush();

                        $url = $app['url_generator']->generate('user.id', array(
                            "id" => $user->getId(),
                        ));
                        $status = 201;
                    } else {
                        $url = $app['url_generator']->generate('user.id', array(
                            "id" => $result->getId(),
                        ));
                        $data->messageId = "user.exists";
                        $status = 303;
                    }

                    $headers = array(
                        "Location" => $url
                    );
                }
            } else {
                $data->messageId = "user.datainvalid";
                $status = 404;
            }

            return $app->json($data, $status, $headers);
        })->bind('user.add');

    $app->delete('/users/{id}', function($id) use ($app) {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $app['doctrine_orm.em'];
        /** @var $userRepo \Bibi\Repo\UserRepo */
        $userRepo = $em->getRepository("Bibi\Entity\User");
        /** @var $result \Bibi\Entity\User */
        $result = $userRepo->findOneById((int) $id);

        $status = 500;

        $data = new \stdClass();

        if(empty($result)) {
            $status = 410;
            $data->messageId = 'user.notexists';
        } else {
            $em->remove($result);
            $em->flush();
            $status = 200;
            $data->messageId = 'user.removed';
        }

        return $app->json($data, $status);
    })->bind('user.delete')
    ->convert('id', function ($id) { return (int) $id; });

    /*
    * @todo
    *       update user
    *       Application tests
    */

    return $controllers;
    }
}



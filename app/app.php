<?php
use Symfony\Component\HttpFoundation\Response;

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'prod'));

require_once __DIR__.'/bootstrap.php';

$config = require __DIR__ . '/config.php';

$env = APPLICATION_ENV;
$app = new Silex\Application();
$app['debug'] = true;

//register Doctrine ORM extension
/*$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'            => $config['db'][$env],
));*/

$app->register(new Palma\Silex\Provider\DoctrineORMServiceProvider(), array(
    'doctrine_orm.entities_path'     => realpath(__DIR__.'/../src/Bibi/Entity'),
    'doctrine_orm.proxies_path'      => realpath(__DIR__.'/../var/Proxy'),
    'doctrine_orm.proxies_namespace' => 'DoctrineProxy',
    'doctrine_orm.connection_parameters' => array_merge(
        $config['db'][$env],
        array('charset'       => 'utf8')
    )
));


$app->register(new Silex\Provider\ValidatorServiceProvider());
$app['validator.mapping.class_metadata_factory'] = new Symfony\Component\Validator\Mapping\ClassMetadataFactory(
    new Symfony\Component\Validator\Mapping\Loader\YamlFileLoader(__DIR__.'/../data/validation/validation.yml')
);

//ensure that content type is json
$app->before(function ($request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request = new ParameterBag($data);
    }
});

$app->error(function (\Doctrine\ORM\ORMException $e, $code) use ($app) {
    if(!$app['debug']) {
        return $app->json(array('Message' => 'Doctrine failure'), 500);
    } else {
        return $app->json(array(
            'Message' => $e->getMessage(),
            'Trace' => $e->getTraceAsString()
        ), 500);
    }
});

$app->get('/', function() use ($app) {

    $test = new stdClass();
    $test->name = 'foo';

    return $app->json($test);
});

$app->mount('/users', new Bibi\Controller\UserControllerProvider());


return $app;
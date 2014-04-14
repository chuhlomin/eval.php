<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__ . '/../views']);

$app->register(new SilexPhpRedis\PhpRedisProvider(), array(
    'redis.host' => '127.0.0.1',
    'redis.port' => 6379,
    'redis.timeout' => 30,
    'redis.persistent' => true
));

$app['debug'] = true;

$defaultCode = '
<?php

// write your code here';

/**
 * @return string
 */
function getKey()
{
    return substr(md5(uniqid()), 0, rand(7, 12));
}

/**
 * @param $app
 * @return bool
 */
function getIsSaveAvailable($app)
{
    $extensionLoaded = extension_loaded('redis');
    $classExists = class_exists('Redis');

    try {
        $serverIsAlive = $app['redis']->ping();
    }
    catch (Exception $e) {
        $serverIsAlive = false;
    }

    return $extensionLoaded && $classExists && $serverIsAlive;
}

$app->get('/', function () use ($app, $defaultCode) {

    return $app['twig']->render('content.twig', [
        'output' => null,
        'code' => $defaultCode,
        'isSaveAvailable' => getIsSaveAvailable($app)
    ]);
});

$app->get('/load/{id}', function ($id) use ($app, $defaultCode) {

    $code = $app['redis']->get($id);

    return json_encode([
        'success' => true,
        'code' => $code
    ]);

});

$app->post('/eval', function (Request $request) use ($app) {
    $code = $request->request->get('code');

    $clearCode = substr($code, 5);

    ob_start();
    try {
        eval($clearCode);
    }
    catch (Exception $e) {
        echo $e->getCode();
    }
    $output = ob_get_clean();

    return json_encode([
        'success' => true,
        'output' => $output
    ]);
});

$app->post('/save', function (Request $request) use ($app) {
    $code = $request->request->get('code');

    $key = getKey();

    $success = $app['redis']->setnx($key, $code);

    return json_encode([
        'success' => $success,
        'result' => $key
    ]);
});

$app->run();
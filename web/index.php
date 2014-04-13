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

abstract class Boo {
	private $field;
}

class Foo extends Boo {

}

$variable = new Foo();
$variable->field = \'string\';

print_r($variable->field);';

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

$app->post('/', function (Request $request) use ($app) {
    $code = $request->request->get('code');

    $clearCode = substr($code, 5);

    ob_start();
    eval($clearCode);
    $output = ob_get_clean();

    return $app['twig']->render('content.twig', [
        'code' => $code,
        'output' => $output,
        'isSaveAvailable' => getIsSaveAvailable($app)
    ]);
});

$app->post('/save', function (Request $request) use ($app) {
    $code = $request->request->get('code');

    $key = getKey();

    /** @var Redis $redis */
    $redis = $app['redis'];

    $success = $redis->setnx($key, $code);

    return json_encode([
        'success' => $success,
        'result' => $key
    ]);
});

$app->run();
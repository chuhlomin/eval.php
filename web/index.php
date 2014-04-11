<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), ['twig.path' => __DIR__.'/../views']);

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

$app->get('/', function() use ($app, $defaultCode) {

    return $app['twig']->render('content.twig', [
        'output' => null,
        'code' => $defaultCode,
    ]);
});

$app->post('/', function(Request $request) use ($app) {
    $code = $request->request->get('code');

    $clearCode = substr($code, 5);

    ob_start();
    eval($clearCode);
    $output = ob_get_clean();

    return $app['twig']->render('content.twig', [
        'code' => $code,
        'output' => $output
    ]);
});

$app->run();
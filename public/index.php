<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->write('Welcome to Slim!');
});

$users = ['mike', 'mishel', 'adel', 'keks', 'kamila', 'roman', 'ella'];

$app->get('/users', function ($request, $response, $args) use ($users) {
    $params = [
        'users' => $users
    ];
    $term = $request->getQueryParam('term');
    if (isset($term)) {
        $params = [
            'users' => array_filter($users, fn($user) => strpos($user, $term) !== false),
            'term' => $term
        ];
    }

    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});

$app->get('/courses/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    return $response->write("Course id: {$id}");
});

$app->get('/users/{id}', function ($request, $response, $args) {
    $params = ['id' => $args['id'], 'nickname' => 'user-' . $args['id']];
    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
});

$app->run();

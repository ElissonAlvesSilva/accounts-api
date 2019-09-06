<?php declare(strict_types=1);

$namespace = 'App\Controllers';

$app->group('/accounts/v1', function () use ($app, $namespace) {

  // $app->group('/auth', function () use ($app, $namespace) {
  //   $app->get('', $namespace . '\AuthController:getAll');
  //   $app->get('', $namespace . '\AuthController:getAll');
  // });
  
  $app->group('/users', function () use ($app, $namespace) {
    require __DIR__ . '/../validators/users.php';
    require __DIR__ . '/../validators/password.php';
    $app->get('', $namespace . '\UsersController:getAll');
    $app->get('/[{id}]', $namespace . '\UsersController:getById');
    $app->post('', $namespace . '\UsersController:create')->add(new \DavidePastore\Slim\Validation\Validation($users));
    $app->put('/[{id}]', $namespace . '\UsersController:update')->add(new \DavidePastore\Slim\Validation\Validation($password));
    $app->delete('/[{id}]', $namespace . '\UsersController:delete');
  });

  $app->group('/applications', function () use ($app, $namespace) {
    require __DIR__ . '/../validators/applications.php';

    $app->get('', $namespace . '\ApplicationsController:getAll');
    $app->get('/[{id}]', $namespace . '\ApplicationsController:getById');
    $app->post('', $namespace . '\ApplicationsController:create')->add(new \DavidePastore\Slim\Validation\Validation($applications));
    $app->put('/[{id}]', $namespace . '\ApplicationsController:update');
    $app->delete('/[{id}]', $namespace . '\ApplicationsController:delete');
  });

  $app->group('/permissions', function () use ($app, $namespace) {
    require __DIR__ . '/../validators/permissions.php';

    $app->get('', $namespace . '\PermissionsController:getAll');
    $app->get('/[{id}]', $namespace . '\PermissionsController:getById');
    $app->post('', $namespace . '\PermissionsController:create')->add(new \DavidePastore\Slim\Validation\Validation($permissions));
    $app->put('/[{id}]', $namespace . '\PermissionsController:update');
    $app->delete('/[{id}]', $namespace . '\PermissionsController:delete');
  });


  // $app->group('/recovery', function () use ($app, $namespace) {
  //   $app->get('/[{token}]', $namespace . '\RecoveryController:getById');
  //   $app->post('', $namespace . '\RecoveryController:create');
  //   $app->put('/[{token}]', $namespace . '\RecoveryController:update');
  // });
});

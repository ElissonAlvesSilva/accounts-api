<?php
$container['UsersController'] = function ($container) {
  return App\Controllers\UsersController($container);
};

$container['ApplicationsController'] = function ($container) {
  return App\Controllers\ApplicationsController($container);
};

$container['PermissionsController'] = function ($container) {
  return App\Controllers\PermissionsController($container);
};
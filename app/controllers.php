<?php
$container['UsersController'] = function ($container) {
  return App\Controllers\UsersController($container);
};
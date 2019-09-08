<?php 

use Respect\Validation\Validator as v;

$applications = [
  'name' => v::notBlank(),
  'url' => v::notBlank(),
  'url_login' => v::notBlank(),
  'url_logout' => v::notBlank(),
];
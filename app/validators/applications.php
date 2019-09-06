<?php 

use Respect\Validation\Validator as v;

$applications = [
  'name' => v::notBlank(),
  'url' => v::notBlank(),
  'url_login' => v::notBlank(),
  'logout_url' => v::notBlank(),
  'token' => v::notBlank(),
];
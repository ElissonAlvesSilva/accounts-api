<?php 

use Respect\Validation\Validator as v;


$auth = [
  'email' => v::notBlank(),
  'password' => v::notBlank(),
];

$token = [
  'token'=> v::notBlank(),
];
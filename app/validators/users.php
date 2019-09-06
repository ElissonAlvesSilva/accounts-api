<?php 

use Respect\Validation\Validator as v;

$users = [
  'name' => v::notBlank(),
  'email' => v::email(),
  'password' => v::notBlank(),
  'status' => v::notBlank(),
  'recovery_password' => v::optional(v::stringType()),
  'create_at' => v::optional(v::date()),
  'update_at' => v::optional(v::date()),
];
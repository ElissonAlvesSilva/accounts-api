<?php

use Respect\Validation\Validator as v;


$permissions = [
  'user_id' => v::notBlank(),
  'application_id' => v::notBlank(),
];
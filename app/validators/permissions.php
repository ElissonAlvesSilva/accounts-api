<?php

use Respect\Validation\Validator as v;


$permissions = [
  'user_id' => v::notBlank(),
  'applications_id' => v::notBlank(),
];
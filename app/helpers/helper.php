<?php 

namespace App\Helpers;

use \Firebase\JWT\JWT;

class Helpers
{
  public function generateJWT($params) {
    return JWT::encode($params, SECRET_KEY);
  }

  public function decodeJWT($token) {
    return JWT::decode($token, SECRET_KEY, array('HS256'));
  }
}

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

  public function getBearerToken(string $headers) {
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
  }
}

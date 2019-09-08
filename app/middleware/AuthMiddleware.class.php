<?php 

namespace App\Middleware;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\Users;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthMiddleware extends BaseController
{
  public function __invoke($request, $response, $next)
  {
    
    $this->setParams($request, $response, []);
    $headers = $this->request->getHeader('Authorization');
    $bearer = $this->getBearerToken($headers[0]);
    try {
      $token = Helpers::decodeJWT($bearer);
    } catch (Exception $e) {
      return $this->jsonResponse($e->getMessage(), 410);
    }
    if(isset($token)) {
      try {
        $this->validateUser($token);
        $response = $next($request, $response);
        return $response;
      } catch (Exception $e) {
        return $this->jsonResponse($e->getMessage(), 403);
      }
    }

  }

  private function getBearerToken(string $headers) {
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
  }

  private function validateUser($token) {
    try {
      Users::where('id', $token->data->id)
        ->where('email', $token->data->email)
        ->firstOrFail();
    } catch (ModelNotFoundException $e) {
      throw new Exception('unauthorized');
    }
  }
}

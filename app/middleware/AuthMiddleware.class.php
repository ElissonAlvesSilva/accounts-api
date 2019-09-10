<?php 

namespace App\Middleware;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\Permissions;
use App\Models\Users;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthMiddleware extends BaseController
{
  public function __invoke($request, $response, $next)
  {
    
    $this->setParams($request, $response, []);
    $headers = $this->request->getHeader('Authorization');
    if(!isset($headers[0])) {
      return $this->jsonResponse('bearer must be pass', 403);
    }
    
    $bearer = Helpers::getBearerToken($headers[0]);
    try {
      $token = Helpers::decodeJWT($bearer);
    } catch (Exception $e) {
      return $this->jsonResponse($e->getMessage(), 410);
    }

    if(isset($token)) {
      try {
        $valid = $this->validateUser($token);
        $response = $next($request, $response);
        return $response;
      } catch (Exception $e) {
        return $this->jsonResponse($e->getMessage(), 403);
      }
    }

  }

  private function validateRoles($token) {
    try {
      foreach ($token->data->roles as $value) {
        Permissions::where('id', $value->id)
        ->firstOrFail();
      }
    } catch (ModelNotFoundException $e) {
      throw new Exception('unauthorized');
    }
  }

  private function validateUser($token) {
    try {
      Users::where('id', $token->data->id)
        ->where('email', $token->data->email)
        ->firstOrFail();
      return true;
    } catch (ModelNotFoundException $e) {
      throw new Exception('unauthorized');
    }
  }
}

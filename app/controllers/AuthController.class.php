<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Helpers\Helpers;
use App\Models\Permissions;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends BaseController {

 public function login($request, $response, $args)
 {
    $this->setParams($request, $response, $args);
    if($request->getAttribute('has_errors')) {
      $code = 400;
      $errors = $request->getAttribute('errors');

      return $this->jsonResponse($errors, $code);
    }
    $input = $this->getInput();
    try {
      $user = $this->validate($input);
      $user = $this->sendData($user);
      return $this->jsonResponse($user, 200);
    } catch (Exception $e) {
      return $this->jsonResponse($e->getMessage(), 403);
    }

 }

 public function hasPermissions($request, $response, $args) {

  $this->setParams($request, $response, $args);
  $headers = $this->request->getHeader('Authorization');
  $input = $this->getInput();
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
      $this->validateUser($token);
      return $this->jsonResponse(true, 200);
    } catch (Exception $e) {
      return $this->jsonResponse($e->getMessage(), 403);
    }
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

 private function validate($input) {
   try {
     $user = Users::where('email', $input['email'])
                    ->where('password', hash('sha256', $input['password'] . PASSWORD_SECRET_KEY))
                    ->firstOrFail();
     return $user;
   } catch (ModelNotFoundException $e) {
     throw new Exception('unauthorized');
   }
 }

 private function getRoles($user) {
   return Permissions::where('user_id', $user['id'])->get(); 
 }

 private function sendData($data) {
  $issuedat_claim = time(); // issued at
  $notbefore_claim = $issuedat_claim + 10; //not before in seconds
  $expire_claim = $issuedat_claim + (30 * 60); // expire time in seconds
  $payload = array(
      "iat" => $issuedat_claim,
      "nbf" => $notbefore_claim,
      "exp" => $expire_claim,
      "data" => array(
          "id" => $data['id'],
          "name" => $data['name'],
          "email" => $data['email'],
          "roles" => $this->getRoles($data),
          
  ));
  return [
    'email' => $data['email'],
    'token' => Helpers::generateJWT($payload),
    'exp' => $expire_claim
  ];
 }
}
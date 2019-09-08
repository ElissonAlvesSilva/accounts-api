<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Helpers\Helpers;
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
  ));
  return [
    'email' => $data['email'],
    'token' => Helpers::generateJWT($payload),
    'exp' => $expire_claim
  ];
 }
}
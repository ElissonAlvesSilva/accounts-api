<?php

namespace App\Controllers;

use App\Models\Users;

class RecoveryController extends BaseController
{
  public function recovery($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    $input = $this->getInput();

    if($request->getAttribute('has_errors')) {
      $code = 400;
      $errors = $request->getAttribute('errors');

      return $this->jsonResponse($errors, $code);
    }
    $token = md5($input['email'] . PASSWORD_SECRET_KEY);
    try {
      Users::where('email', $input['email'])->update([
        'recovery_password' => $token,
        'status' => 3
      ]);
      return $this->jsonResponse($input, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e->getMessage(), 400);
    }
  }

  public function update($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    $input = $this->getInput();

    if($request->getAttribute('has_errors')) {
      $code = 400;
      $errors = $request->getAttribute('errors');

      return $this->jsonResponse($errors, $code);
    }
    $token = md5($input['email'] . PASSWORD_SECRET_KEY);
    if (strcmp($token, $this->args['token']) == 0) {
      Users::where('email', $input['email'])->update([
        'password' => hash('sha256', $input['password'] . PASSWORD_SECRET_KEY),
        'recovery_password' => '',
        'status' => 1
      ]);
      return $this->jsonResponse($input, http_response_code());
    }
    return $this->jsonResponse('invalid token', 403);
  }
}

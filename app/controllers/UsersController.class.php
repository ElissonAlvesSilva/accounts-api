<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Permissions;
use App\Models\Users;

class UsersController extends BaseController
{
  public function getAll($request, $response, $args)
  {
    $all = Users::all();
    return $response->getBody()->write($all->toJson());
  }

  public function getById($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    try {
      $user = Users::findOrFail($this->args['id']);
      $user['permissions'] = $this->getPermissions($this->args['id']);
      return $this->jsonResponse($user, 200);
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  public function create($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    $input = $this->getInput();

    $duplicate = Users::where('email', $input['email'])->first();

    if (!$duplicate) {
      if($request->getAttribute('has_errors')) {
        $code = 400;
        $errors = $request->getAttribute('errors');

        return $this->jsonResponse($errors, $code);
      }
      $input['password'] = hash('sha256', $input['password'] . PASSWORD_SECRET_KEY);
      $user = Users::create($input);
    }
      
    return $this->jsonResponse($user, 200);
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

    try {
      Users::findOrFail($this->args['id'])->update($input);
      return $this->jsonResponse($input, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  public function delete($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    try{
      $user = Users::findOrFail($this->args['id'])->delete();
      return $this->jsonResponse($user, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  private function getPermissions($id) {
    $permissions = Permissions::where('user_id', $id)->get();
    return $permissions;
  }
}

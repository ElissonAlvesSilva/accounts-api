<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Permissions;

class PermissionsController extends BaseController
{
  public function getAll($request, $response, $args)
  {
    $all = Permissions::all();
    return $response->getBody()->write($all->toJson());
  }

  public function getById($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    try {
      $user = Permissions::findOrFail($this->args['id']);
      return $this->jsonResponse($user, 200);
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  public function create($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    $input = $this->getInput();

    $duplicate = Permissions::where('user_id', $input['user_id'])
                            ->where('application_id', $input['application_id'])
                            ->first();

    if (!$duplicate) {
      if($request->getAttribute('has_errors')) {
        $code = 400;
        $errors = $request->getAttribute('errors');

        return $this->jsonResponse($errors, $code);
      }
      $user = Permissions::create($input);
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
      Permissions::findOrFail($this->args['id'])->update($input);
      return $this->jsonResponse($input, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  public function delete($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    try{
      $user = Permissions::findOrFail($this->args['id'])->delete();
      return $this->jsonResponse($user, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }
}

<?php declare(strict_types=1);

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseController
{
    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var Response $response
     */
    protected $response;

    /**
     * @var array $args
     */
    protected $args;

    protected function setParams(Request $request, Response $response, array $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }
    /**
     * @param string $status
     * @param mixed $message
     * @param int $code
     * @return Response
     */
    protected function jsonResponse($message, int $code):  Response
    {
        $result = [];
        if ($code < 400) {
            $result = [
                'code' => $code,
                'response' => $message,
            ];
        } else {
            $result = [
                'code' => $code,
                'message' => $message,
            ];
        }
        return $this->response->withJson($result, $code, JSON_PRETTY_PRINT);
    }
    /**
     * @return array
     */
    protected function getInput()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return file
     */
    protected function getInputFile()
    {
        return $this->request->getUploadedFiles();
    }

}
<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $a_response = array(
        "code" => 500,
        "message" => "Error desconocido"
    );
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            $this->a_response["message"] = "token is expired";
            return response()->json($this->a_response, 400);
        } elseif ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            $this->a_response["message"] = "token is invalid";
            return response()->json($this->a_response, 400);
        } elseif ($exception instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
            $this->a_response["message"] = "token absent";
            return response()->json($this->a_response, 400);
        } else {
            if ($exception->getCode()==23000) {
                $this->a_response["message"] = "Database UNIQUE constraint exception. Maybe one field data already exists, please verify your data.";
            } else {
                $this->a_response["message"] = $exception->getMessage();
            }
            return response()->json($this->a_response, 500);
        }
        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $this->a_response["code"] = 401;
        $this->a_response["message"] = "You are not authorized";
        return response()->json($this->a_response, 401);
    }
}

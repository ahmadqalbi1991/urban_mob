<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
        /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'status' => '1',
            'message' => $message,
            'data'    => $result,
            'errors'    => (object) [],
            'error'    => '',
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
    	$response = [
            'status' => '0',
            'message' => $error,
            'error' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }else{
            $response['data'] = (object) [];
        }


        return response()->json($response, $code);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function __construct() {
        Parent::__construct();
    }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($dataSet)
    {
    	$response = [
            'success' => true,
            'data'    => $dataSet,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'errors' => $error,
        ];


        if(!empty($errorMessages)){
            $response['message'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * sucess method response
     * 
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result,$message,$status=200){
       $response = [
         'success' => true,
         'message' => $message,
         'data' => $result
       ];
       return response()->json($response,$status);
    }
    /**
     * error method
     * @return \Illuminate\Http\Response
     */
    
    public function sendError($error,$errorMessages = [], $code){
         $response = [
            'success' => false,
            'message' => $error
         ];
         if(!empty($errorMessages)){
              $response['data'] = $errorMessages;
         }
         return response()->json($response,$code);
         
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($data, $message, $code = 200)
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $code);
    }

    public function sendError($errorData = [], $message, $code = 400)
    {
        $error = [
            'code' => $code,
            'message' => $message
        ];

        if (!empty($errorData))
        {
            $error['data'] = $errorData;
        }

        return response()->json($error, $code);
    }
}

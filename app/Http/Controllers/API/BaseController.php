<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * Success JSON response.
     *
     * @param mixed $result
     * @param string $message
     * @param integer $statusCode
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, string $message, int $statusCode = 200)
    {
        /**
         * Response that needs to send.
         */
        $response = [
            'status'  => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Error JSON response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param integer $statusCode
     * @return \Illuminate\Http\Response
     */
    public function sendError(string $error, array $errorMessages = [], int $statusCode = 404)
    {
        /**
         * Response that needs to send.
         */
        $response = [
            'status' => false,
            'error'  => $error,
        ];

        /**
         * Checks and load error messages if they exist.
         */
        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $statusCode);
    }
}
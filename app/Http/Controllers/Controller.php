<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Return a JSON response with a standardized structure.
     *
     * @param string $message
     * @param array $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function json(
        $status = 200,
        $message = 'Success',
        $data = []
    ): \Illuminate\Http\JsonResponse {
        return response()->json(
            data: [
                'status' => $status < 400,
                'message' => $message,
                'data' => $data,
            ],
            status: $status,
        );
    }
}

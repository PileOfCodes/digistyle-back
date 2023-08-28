<?php

namespace App\Traits;

trait apiResponse {
    protected function succesResponse($message = "",$code = 200, $data) {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $data
        ], $code);
    }
    protected function errorResponse($message = "",$code, $data = "") {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data
        ], $code);
    }
}
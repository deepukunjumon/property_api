<?php

class Response {

    public static function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;
    }

    public static function error($message, $status) {
        self::json([
            'success' => false,
            'error' => $message
        ], $status);
    }

    public static function success($data, $status = 200) {
        self::json([
            'success' => true,
            'data' => $data
        ], $status);
    }
}
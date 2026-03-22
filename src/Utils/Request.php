<?php

class Request {
    private static array $args = [];

    /**
     * Get JSON body as associative array
     *
     * @return array
     */
    public static function body() {
        $input = file_get_contents("php://input");
        return json_decode($input, true) ?? [];
    }

    /**
     * Get query parameters
     *
     * @return array
     */
    public static function query() {
        return $_GET;
    }

    /**
     * Store arguments
     */
    public static function setArgs(array $args): void {
        self::$args = $args;
    }

    /**
     * Retrieve a specific argument by name
     */
    public static function getArg(string $name, $default = null) {
        return self::$args[$name] ?? $default;
    }
}
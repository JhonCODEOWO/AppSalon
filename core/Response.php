<?php

namespace Core;

class Response {
    static function json(array | object $data, int $http_code) {
        http_response_code($http_code);
        header("Content-Type: application/json");

        echo json_encode($data);
        exit;
    }
}
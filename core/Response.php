<?php

namespace Core;

class Response {
    static function json(array $data, int $http_code) {
        http_response_code($http_code);

        echo json_encode($data);
        exit;
    }
}
<?php

namespace App\Controllers;

use App\Utils\Security;

class Controller
{
    protected function json($data, $status = 200)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: DENY');
            header('X-XSS-Protection: 1; mode=block');
            http_response_code($status);
        }
        echo json_encode(Security::escape($data));
    }

    protected function view($name, $data = [])
    {
        $data = Security::escape($data);
        extract($data);
        ob_start();
        require_once __DIR__ . "/../Views/$name.php";
        $content = ob_get_clean();
        require_once __DIR__ . "/../Views/layout.php";
    }

    protected function getRequestData()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?? $_POST;
        return Security::sanitizeInput($data);
    }
}

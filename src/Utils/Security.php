<?php

namespace App\Utils;

class Security
{
    public static function escape($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::escape($value);
            }
            return $data;
        }
        
        // Проверяем на null и конвертируем в строку если нужно
        if ($data === null) {
            return '';
        }
        
        // Конвертируем в строку, если это не строка
        if (!is_string($data)) {
            $data = (string)$data;
        }
        
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeInput($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        
        if ($data === null) {
            return '';
        }
        
        if (!is_string($data)) {
            $data = (string)$data;
        }
        
        return strip_tags(trim($data));
    }
}

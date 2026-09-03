<?php

namespace App\Services;

class AppSetting
{
    private static function getPath()
    {
        return storage_path('app/settings.json');
    }

    public static function get(string $key, $default = null)
    {
        $path = self::getPath();
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            return $data[$key] ?? $default;
        }
        return $default;
    }

    public static function set(string $key, $value): void
    {
        $path = self::getPath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $data = [];
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true) ?? [];
        }
        
        $data[$key] = $value;
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }
}

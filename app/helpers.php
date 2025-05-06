<?php

if (!function_exists('importCsv')) {
    function importCsv($filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}

if (!function_exists('overWriteEnvFile')) {
    function overWriteEnvFile($type, $field)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($field) . '"';
            if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                file_put_contents($path, str_replace($type . '="' . env($type) . '"', $type . '="' . $field . '"', file_get_contents($path)));
            } else {
                file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '="' . $field . '"');
            }
        }
    }
}

// Return current tenant
if (!function_exists('tenant')) {
    function tenant()
    {
        return app('tenant');
    }
}

// Check if tenant is set or not
if (!function_exists('isTenant')) {
    function isTenant(): bool
    {
        if (!app()->bound('tenant')) return false;

        return optional(app('tenant'))->id;
    }
}

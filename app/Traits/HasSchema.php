<?php

namespace App\Traits;

trait HasSchema
{
    static function getSchema()
    {
        $file_root = base_path('./orm-classes/src/schemas');
        $file_path = $file_root . '/' . substr(strrchr(__CLASS__, "\\"), 1) . '.json';

        $jsonSchemaString = file_get_contents($file_path);

        return json_decode($jsonSchemaString, true);
    }
}

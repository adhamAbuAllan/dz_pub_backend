<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function clean($data)
    {
        // Convert models to array
        if ($data instanceof \JsonSerializable) {
            $data = $data->jsonSerialize();
        } elseif (is_object($data)) {
            $data = (array) $data;
        }

        // Process arrays
        if (is_array($data)) {
            foreach ($data as $key => $value) {

                // Recursively clean nested arrays
                $data[$key] = self::clean($value);

                // Remove null or empty array
                if ($data[$key] === null || $data[$key] === []) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }
}

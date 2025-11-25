<?php

namespace App\Helpers;

class ValidationMessages
{
    /**
     * Generate custom messages for fields per table
     *
     * @param string $table
     * @param array $fields
     * @return array
     */
    public static function requiredMessages(string $table, array $fields): array
    {
        $messages = [];
        foreach ($fields as $field) {
            $messages["$field.required"] = "The $field field on $table should not be null.";
        }
        return $messages;
    }
}

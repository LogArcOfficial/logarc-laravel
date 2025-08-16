<?php

namespace Dipesh79\LogArcLaravel\Service;

class DataFormatter
{
    /**
     * Detect the type of the provided data.
     *
     * @param array|string $data
     * @return string
     */
    public static function detectType(array|string $data): string
    {
        if (is_array($data)) {
            return 'array';
        }
        return 'string';
    }

}

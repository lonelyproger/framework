<?php

namespace Lonelyproger\Framework\Core;

if (! function_exists('Lonelyproger\Framework\Core\join_paths')) {
    /**
     * Join the given paths together.
     *
     * @param  string|null  $basePath
     * @param  string  ...$paths
     * @return string
     */
    function join_paths($basePath, ...$paths)
    {
        foreach ($paths as $index => $path) {
            if (empty($path)) {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath . implode('', $paths);
    }
}
if (! function_exists('Lonelyproger\Framework\Core\between')) {
    /**
     * Get all portions of a string between two given values.
     *
     * @param  string  $haystack
     * @param  string  $from
     * @param  string  $to
     * @return array
     */
    function between($haystack, $from, $to)
    {
        $crop_left = array_reverse(explode($from, $haystack));
        array_pop($crop_left);
        foreach ($crop_left as $value)
            $result[] = substr($value, 0, strpos($value, $to));
        return $result ?? false;
    }
}

<?php

if (!function_exists('test_file_path')) {
    /**
     * @param  string  $filePath
     *
     * @return string
     * @codeCoverageIgnore
     */
    function test_file_path(string $filePath)
    {
        return base_path('test_default_files'.DIRECTORY_SEPARATOR.$filePath);
    }
}

if (!function_exists('is_class_uses_deep')) {
    /**
     * @param $class
     * @param  string  $trait
     * @param  bool  $autoload
     *
     * @return bool
     */
    function is_class_uses_deep($class, string $trait, $autoload = true): bool
    {
        $traits = [];

        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        $traits = array_unique($traits);

        return in_array($trait, $traits);
    }
}



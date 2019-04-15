<?php

if (!function_exists('testFilePath')) {
    /**
     * @param  string  $filePath
     *
     * @return string
     * @codeCoverageIgnore
     */
    function testFilePath(string $filePath)
    {
        return base_path('test_default_files'.DIRECTORY_SEPARATOR.$filePath);
    }
}

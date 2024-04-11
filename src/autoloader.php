<?php

function autoload_src()
{
    $directory = WP_SIMPLE_RESERVATION_DIRECTORY . 'src/';

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory)
    );

    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'autoloader.php' && strpos($file->getPathname(), '/views/') === false) {
            require_once $file->getPathname();
        }
    }
}

autoload_src();

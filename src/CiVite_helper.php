<?php

if (!function_exists('vite')) {
    /**
     * Helper function to render Vite assets
     *
     * @param array $modules List of module paths to include
     * @return string HTML tags for assets
     */
    function vite(array $modules = []): string
    {
        // Ensure the class is loaded
        if (!class_exists('\Mountz\CiVite\CiVite')) {
            // Try to load via Composer autoloader first
            if (file_exists(ROOTPATH . 'vendor/autoload.php')) {
                require_once ROOTPATH . 'vendor/autoload.php';
            }
            
            // If still not loaded, try to include the class file directly
            if (!class_exists('\Mountz\CiVite\CiVite')) {
                $classPath = VENDORPATH . 'mountz/civite/src/CiVite.php';
                if (file_exists($classPath)) {
                    require_once $classPath;
                }
            }
        }
        
        return \Mountz\CiVite\CiVite::render($modules);
    }
}
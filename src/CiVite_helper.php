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
        return \Mountz\CiVite\CiVite::render($modules);
    }
}
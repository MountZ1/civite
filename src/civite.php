<?php

namespace Mountz\CiVite;

/**
 * CodeIgniter Vite integration helper
 * Handles asset compilation and hot module reloading for development
 */
class CiVite
{
    private static ?string $host = null;
    private static ?string $publicPath = null;
    private static array $defaultModules = [
        'resources/css/app.css',
        'resources/js/app.js',
    ];

    /**
     * Render Vite assets for the given modules
     *
     * @param array $modules List of module paths to include
     * @return string HTML tags for assets
     */
    public static function render(array $modules = []): string
    {
        $modules = self::mergeWithDefaults($modules);
        $publicPath = self::getPublicPath();

        if (self::isHotReloadActive($publicPath)) {
            return self::renderHotAssets($modules);
        }

        return self::renderProductionAssets($modules, $publicPath);
    }

    /**
     * Check if hot reload is active
     */
    private static function isHotReloadActive(string $publicPath): bool
    {
        return file_exists($publicPath . 'hot');
    }

    /**
     * Render assets for hot reload development mode
     */
    private static function renderHotAssets(array $modules): string
    {
        $host = self::getHost();
        $tags = '<script type="module" src="' . $host . '/@vite/client"></script>' . "\n";
        
        foreach ($modules as $module) {
            $tags .= '<script type="module" src="' . $host . '/' . $module . '"></script>' . "\n";
        }
        
        return $tags;
    }

    /**
     * Render assets for production mode using manifest
     */
    private static function renderProductionAssets(array $modules, string $publicPath): string
    {
        $manifestPath = $publicPath . 'build/manifest.json';
        
        if (!file_exists($manifestPath)) {
            return '<!-- Vite manifest not found. Run "npm run build" first. -->';
        }

        $manifest = self::loadManifest($manifestPath);
        if ($manifest === null) {
            return '<!-- Failed to parse Vite manifest. -->';
        }

        return self::buildAssetTags($modules, $manifest);
    }

    /**
     * Load and parse the Vite manifest file
     */
    private static function loadManifest(string $manifestPath): ?array
    {
        $content = file_get_contents($manifestPath);
        if ($content === false) {
            return null;
        }

        $manifest = json_decode($content, true);
        return json_last_error() === JSON_ERROR_NONE ? $manifest : null;
    }

    /**
     * Build HTML tags for production assets
     */
    private static function buildAssetTags(array $modules, array $manifest): string
    {
        $tags = '';
        
        foreach ($modules as $module) {
            if (!isset($manifest[$module])) {
                continue;
            }

            $entry = $manifest[$module];
            
            if (isset($entry['css'])) {
                foreach ($entry['css'] as $cssFile) {
                    $tags .= '<link rel="stylesheet" href="/build/' . $cssFile . '">' . "\n";
                }
            }
            
            $tags .= '<script type="module" src="/build/' . $entry['file'] . '"></script>' . "\n";
        }
        
        return $tags;
    }

    /**
     * Get the Vite development server host
     */
    private static function getHost(): string
    {
        if (self::$host === null) {
            self::$host = function_exists('env') 
                ? env('VITE_ORIGIN', 'http://localhost:5173')
                : 'http://localhost:5173';
        }
        
        return self::$host;
    }

    /**
     * Get the public path for assets
     */
    private static function getPublicPath(): string
    {
        if (self::$publicPath === null) {
            self::$publicPath = defined('FCPATH') 
                ? FCPATH 
                : getcwd() . '/public/';
        }
        
        return self::$publicPath;
    }

    /**
     * Merge user modules with default modules
     */
    private static function mergeWithDefaults(array $modules): array
    {
        return array_unique(array_merge(self::$defaultModules, $modules));
    }

    /**
     * Reset static properties (useful for testing)
     */
    public static function reset(): void
    {
        self::$host = null;
        self::$publicPath = null;
    }
}

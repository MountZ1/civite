<?php

namespace Mountz\CiVite;

class CiVite
{
  protected static string $host = env('VITE_ORIGIN', 'http://localhost:5173');
  protected static array $modules = [];
  private static $isHot = false;
  private static string $publicPath = defined('FCPATH') ? FCPATH : getcwd() . '/public/';

  public static function render(array $modules = [])
  {
    self::$modules = $modules;
    self::$isHot = file_exists(self::$publicPath . 'hot');
    self::combineModule();

    if (self::$isHot) {
      $tags = '';
      $tags .= '<script type="module" src="' . self::$host . '/@vite/client"></script>';
      foreach (self::$modules as $module) {
        $tags .= '<script type="module" src="' . self::$host . '/' . $module . '"></script>';
      }
      return $tags;
    }

    $manifestPath = self::$publicPath . 'build/manifest.json';
    if (! file_exists($manifestPath)) {
      return '<!-- Vite manifest not found. Run "npm run build" first. -->';
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);
    $tags = '';
    foreach (self::$modules as $module) {
      if (! isset($manifest[$module])) {
        continue;
      }
      if (isset($manifest[$module]['css'])) {
        foreach ($manifest[$module]['css'] as $cssFile) {
          $tags .= '<link rel="stylesheet" href="/build/' . $cssFile . '">';
        }
      }
      $tags .= '<script type="module" src="/build/' . $manifest[$module]['file'] . '"></script>';
    }
    return $tags;
  }

  private static function combineModule(): void
  {
    $defaultModules = [
      'resources/css/app.css',
      'resources/js/app.js',
    ];

    self::$modules = array_unique(array_merge($defaultModules, self::$modules));
  }
}

if (!function_exists('vite')) {
  function vite(array $modules = [])
  {
    return \Mountz\CiVite\CiVite::render($modules);
  }
}

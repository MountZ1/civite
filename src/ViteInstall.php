<?php

namespace Mountz\CiVite\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ViteInstall extends BaseCommand
{
  protected $group       = 'CiVite';
  protected $name        = 'vite:install';
  protected $description = 'Set up Vite config, Tailwind, and entry files.';

  public function run(array $params)
  {
    $basePath = ROOTPATH;

    $folders = [
      $basePath . 'resources/css',
      $basePath . 'resources/js',
    ];

    foreach ($folders as $folder) {
      if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
        CLI::write("Created directory: {$folder}", 'green');
      }
    }

    $files = [
      $basePath . 'resources/css/app.css' => <<<CSS
@import tailwindcss;
CSS,

      $basePath . 'resources/js/app.js' => <<<JS
// Entry point JS
console.log('Vite with CodeIgniter loaded');
JS,
    ];

    foreach ($files as $path => $content) {
      if (!file_exists($path)) {
        file_put_contents($path, $content);
        CLI::write("Created file: {$path}", 'yellow');
      }
    }

    CLI::write('✅ Vite entry files generated successfully.', 'green');
  }
}

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
@import "tailwindcss";
CSS,

      $basePath . 'resources/js/app.js' => <<<JS
import "../css/app.css";

console.log('Vite with CodeIgniter loaded');
JS,
    ];

    foreach ($files as $path => $content) {
      if (!file_exists($path)) {
        file_put_contents($path, $content);
        CLI::write("Created file: {$path}", 'yellow');
      }
    }

    $packageJson = <<<JSON
{
  "private": true,
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build"
  },
  "dependencies": {
    "@tailwindcss/vite": "^4.1.8",
    "laravel-vite-plugin": "^1.3.0",
    "tailwindcss": "^4.1.8",
    "vite": "^6.3.5"
  },
  "devDependencies": {
    "@types/bun": "latest"
  },
  "peerDependencies": {
    "typescript": "^5"
  }
}
JSON;

        $viteConfig = <<<JS
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { defineConfig } from 'vite'

export default defineConfig({
  server: {
    host: 'localhost',
    port: 5173,
    hmr: {
      host: 'localhost',
    },
    cors: true,
    origin: 'http://localhost:5173'
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/js/app.js',
        'resources/css/app.css'
      ],
      refresh: true,
    })
  ],
})
JS;

        $written1 = write_file(ROOTPATH . 'package.json', $packageJson);
        $written2 = write_file(ROOTPATH . 'vite.config.js', $viteConfig);

        if ($written1 && $written2) {
            CLI::write('✅ Vite install files generated successfully.', 'green');
        } else {
            CLI::error('❌ Failed to write one or more files.');
        }

    CLI::write('✅ Vite entry files generated successfully.', 'green');
  }
}

# 🔥 CiVite – Vite + Tailwind + CodeIgniter 4 Integration

**CiVite** brings fast front-end development to CodeIgniter 4 using [Vite](https://vitejs.dev/) and Tailwind CSS — with an easy `php spark vite:install` command. No boilerplate, just plug and play!

---

## 📦 Installation

```bash
composer require mountz/civite
```

> ✅ CodeIgniter 4 is required and will be installed automatically if not present.

---

## 🚀 Usage

### 1. Generate `package.json` & `vite.config.js`

Run this once after install:

```bash
php spark vite:init
```

It will generate the following in your project root:

- `package.json`
- `vite.config.js`

Install Node dependencies:

```bash
bun install
# or
npm install
```

Start Vite dev server:

```bash
bun run dev
# or
npm run dev
```

Change vite port:
if u run vite with another port dont forget to add VITE_ORIGIN = localhost:port in your env

---

### 2. Load Vite Assets in Your Views

In your layout/view files:

```php
<?= vite([]) ?>
```

This will:

- Inject `<script type="module" src="...">` during dev
  - By default it will inject code inside resources/
- Load from `build/manifest.json` in production

---

## 🛠 Features

✅ Detects dev mode via `.hot` file  
✅ Works with Bun or Node  
✅ Supports hot module reload  
✅ Autoloads `vite()` helper  
✅ CLI installer (`vite:install`)  
✅ Minimal config  
✅ Tailwind & Laravel Vite Plugin ready

---

## 🔧 Configuration (vite.config.js)

Here's the generated config:

```js
import tailwindcss from '@tailwindcss/vite'
import laravel from 'laravel-vite-plugin'
import { defineConfig } from 'vite'

export default defineConfig({
  server: {
    host: 'localhost',
    port: 5173,
    hmr: { host: 'localhost' },
    cors: true,
    origin: 'http://localhost:5173'
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/main.js',
        'resources/css/app.css'
      ],
      refresh: true,
    })
  ],
})
```

---

## 📁 File Structure

```
/app
/public
/resources
  ├─ css/app.css
  └─ js/app.js
/vite.config.js
/package.json
```

---

## 💡 Requirements

- PHP 8.1+
- CodeIgniter 4.4+
- Bun (`https://bun.sh`) or Node.js

---

```

---

## 📜 License

MIT © Ardi Saputra  
GitHub: [mountz1](https://github.com/mountz1)

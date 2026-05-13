# MA Tools

**ma-tools.com** — Free browser-based web tools for images and PDFs, with a built-in blog and admin panel.

All file processing happens entirely in the browser. Nothing is uploaded to the server.

---

## Features

### Public Tools
| Tool | Description |
|---|---|
| **Image Crop** | Crop JPEG, PNG, and WebP images with freeform or fixed aspect ratios |
| **BG Remover** | AI-powered background removal — runs locally in the browser via ONNX |
| **PDF Merge** | Combine multiple PDF files into one, with drag-and-drop reordering |
| **PDF Sign** | Draw a signature and embed it anywhere on a PDF document |
| **PDF Text** | Add custom text to any PDF — choose font, size, color, and position |
| **Text Editor** | Rich text editor with HTML/plain-text export |

### Blog
- Public blog with pagination and SEO-optimized single post pages
- JSON-LD `Article` structured data on every post

### Admin Panel
- Secure admin-only area at `/admin`
- Blog post CRUD with TipTap rich text editor and per-post SEO fields
- Page SEO management for all tool and static pages

---

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.3) |
| Frontend routing | Inertia.js v3 |
| UI framework | Vue 3 (Composition API, `<script setup>`) |
| Styling | Tailwind CSS v4 via `@tailwindcss/vite` |
| Build | Vite 7 |
| Database | MySQL 8 |
| Container | Docker (php-fpm + nginx in one image) |
| Node | 20.x |

### Key JS Libraries

| Tool | Library |
|---|---|
| Image Crop | `vue-advanced-cropper` |
| BG Remover | `@imgly/background-removal` |
| PDF Merge / Sign / Text | `pdf-lib` |
| Signature drawing | `signature_pad` |
| Text / Blog editor | `@tiptap/vue-3` + extensions |

---

## Requirements

- Docker & Docker Compose (recommended)
- **Or** PHP 8.3, Composer, Node.js 20+, MySQL 8

---

## Getting Started

### 1. Clone and configure

```bash
git clone https://github.com/your-org/web-tools.git
cd web-tools
cp .env.example .env
```

Edit `.env` and set your database credentials:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_tools
DB_USERNAME=root
DB_PASSWORD=secret

APP_URL=http://localhost:8080
```

### 2. Run with Docker (recommended)

```bash
docker compose up --build -d
docker compose exec web-tools-app php artisan key:generate
docker compose exec web-tools-app php artisan migrate --seed
```

The app is now available at **http://localhost:8080**.  
phpMyAdmin is available at **http://localhost:8081**.

### 3. Run locally (without Docker)

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed

# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite
npm run dev
```

---

## Development

### Vite dev server (hot reload)

```bash
# With Docker
docker compose exec web-tools-app npm run dev

# Locally
npm run dev
```

### Useful Artisan commands

```bash
php artisan route:list          # List all routes
php artisan migrate             # Run pending migrations
php artisan migrate:fresh --seed  # Reset and re-seed the database
php artisan db:seed             # Seed admin user only
```

---

## Admin Access

After seeding, an admin user is created. Log in at `/admin/login`.

Default credentials are set in `database/seeders/DatabaseSeeder.php`. Change the password immediately after first login.

Only users with `is_admin = true` in the `users` table can access the admin panel.

---

## Project Structure

```
resources/js/
  Layouts/
    AppLayout.vue      # Public layout: nav + 6 ad slots + footer
    AdminLayout.vue    # Admin layout: sidebar, no ads
  Components/
    Navigation.vue
    Ads/               # TopBar, SecondBar, LeftSidebar, RightSidebar, Bottom, Popup
  Pages/
    Home.vue
    Tools/
      ImageCrop.vue
      ImageBgRemover.vue
      PdfMerge.vue
      PdfSign.vue
      PdfText.vue
      TextEditor.vue
    Blog/
      Index.vue
      Show.vue
    Admin/
      Login.vue
      Dashboard.vue
      Blog/
        Index.vue
        Form.vue

app/Http/
  Controllers/
    ToolController.php
    BlogController.php
    SitemapController.php
    Admin/
      AuthController.php
      BlogController.php
      PageSeoController.php
  Middleware/
    AdminMiddleware.php

config/
  seo.php              # SEO metadata for every tool and static page
```

---

## Routes

### Public

| Method | Path | Description |
|---|---|---|
| GET | `/` | Home / tool grid |
| GET | `/tools/image-crop` | Image Crop tool |
| GET | `/tools/image-bg-remover` | BG Remover tool |
| GET | `/tools/pdf-merge` | PDF Merge tool |
| GET | `/tools/pdf-sign` | PDF Sign tool |
| GET | `/tools/pdf-text` | PDF Text tool |
| GET | `/tools/text-editor` | Text Editor tool |
| GET | `/blog` | Blog listing |
| GET | `/blog/{slug}` | Single blog post |
| GET | `/sitemap.xml` | XML sitemap |
| GET | `/robots.txt` | Robots file |

### Admin

| Method | Path | Description |
|---|---|---|
| GET | `/admin/login` | Admin login page |
| POST | `/admin/login` | Authenticate |
| POST | `/admin/logout` | Log out |
| GET | `/admin` | Dashboard |
| GET/POST | `/admin/blog` | Blog post list / create |
| GET/PUT/DELETE | `/admin/blog/{id}/edit` | Edit / update / delete post |
| GET/PUT | `/admin/seo/{key}/edit` | Edit per-page SEO metadata |

---

## SEO

Every public page receives a `seo` prop from the Laravel controller. Metadata is stored in `config/seo.php` and the `page_seos` database table (editable via the admin panel).

`AppLayout.vue` renders `<Head>` with:
- `<title>`
- `<meta name="description">`
- `<meta name="keywords">`
- Open Graph tags (`og:title`, `og:description`, `og:image`)
- Twitter Card tags

Blog posts additionally include JSON-LD `Article` structured data.

---

## Ads

Six AdSense slots are included in `AppLayout.vue`. Slot IDs are placeholders — replace `ca-pub-XXXXXXXXXX` and the slot numbers in each file under `resources/js/Components/Ads/`.

The popup ad (`Ads/Popup.vue`) is triggered after a successful tool download:

```js
const triggerAdPopup = inject('triggerAdPopup');
// call after download:
triggerAdPopup();
```

---

## Database

| Table | Purpose |
|---|---|
| `users` | Admin accounts (`is_admin` flag) |
| `blog_posts` | Blog content with full SEO fields |
| `page_seos` | Per-page SEO overrides managed via admin |

---

## Docker Services

| Service | Port | Description |
|---|---|---|
| `web-tools-app` | 8080 | Laravel app (php-fpm + nginx) |
| `web-tools-app` | 5173 | Vite dev server (when running `npm run dev` inside container) |

> The database is **external** — configure `DB_HOST` in `.env` to point to your MySQL instance or a separate MySQL container.

---

## License

MIT

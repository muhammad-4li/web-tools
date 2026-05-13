# MA Tools — Copilot Instructions

> **Last updated:** 2026-05-14  
> Update this file whenever architecture, conventions, or key libraries change.

---

## Project Overview

**ma-tools.com** — A public web-tools site with Google AdSense monetization.  
Users can use free browser-based tools (image crop, BG remover, PDF tools, text editor).  
The site also has a blog and an admin panel for blog management.

---

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.3) |
| Frontend routing | Inertia.js v3 |
| UI framework | Vue 3 (Composition API, `<script setup>`) |
| Styling | Tailwind CSS v4 via `@tailwindcss/vite` |
| Build | Vite |
| Database | MySQL 8 |
| Container | Docker (php-fpm + nginx + supervisor in one image) |
| Node | 20.x |

---

## Strict Rules (never violate)

1. **Zero server-side file processing.** All file operations (image crop, BG removal, PDF manipulation) happen in the browser via JS libraries. Files are NEVER uploaded to the server or stored on disk.
2. **Client-side download only.** Results are served via `URL.createObjectURL()` and `<a download>`. No file storage endpoints.
3. **SEO on every page.** Every Inertia page must receive a `seo` prop (title, description, keywords, og_image) and render it via `<Head>` in `AppLayout.vue`.
4. **Ads on every public page.** Public pages use `AppLayout.vue` which includes all 6 ad slots. Admin pages use `AdminLayout.vue` (no ads).
5. **No user accounts on the public site.** No registration, login, or sessions for public users.
6. **Admin-only auth.** Only users with `is_admin = true` can access `/admin/*`. Use `AdminMiddleware`.

---

## Directory Structure

```
resources/js/
  Layouts/
    AppLayout.vue      # Public layout: nav + 6 ad slots + footer
    AdminLayout.vue    # Admin layout: sidebar + main area, no ads
  Components/
    Navigation.vue     # Top nav with all tool links
    Ads/
      TopBar.vue       # Horizontal banner above nav
      SecondBar.vue    # Horizontal banner below nav
      LeftSidebar.vue  # Vertical 160×600 left column
      RightSidebar.vue # Vertical 160×600 right column
      Bottom.vue       # Horizontal banner at footer
      Popup.vue        # Modal popup after tool action
  Pages/
    Home.vue           # Landing page with tool grid
    Tools/
      ImageCrop.vue        # vue-advanced-cropper
      ImageBgRemover.vue   # @imgly/background-removal
      PdfMerge.vue         # pdf-lib
      PdfSign.vue          # pdf-lib + signature_pad
      PdfText.vue          # pdf-lib
      TextEditor.vue       # @tiptap/vue-3
    Blog/
      Index.vue        # Blog listing with pagination
      Show.vue         # Single post with JSON-LD
    Admin/
      Login.vue        # Admin login page (no AppLayout)
      Dashboard.vue    # Stats + quick links
      Blog/
        Index.vue      # Blog post CRUD table
        Form.vue       # Create/edit with TipTap editor + SEO fields
```

---

## JS Libraries — per tool

| Tool | Library | Notes |
|---|---|---|
| Image Crop | `vue-advanced-cropper` | Vue 3 native; `getResult().canvas.toDataURL()` for export |
| BG Remover | `@imgly/background-removal` | Downloads ONNX model on first run (~5 MB from CDN) |
| PDF Merge | `pdf-lib` | `PDFDocument.create()`, `copyPages()`, `save()` |
| PDF Sign | `pdf-lib` + `signature_pad` | Draw on canvas, `toDataURL('image/png')`, embed as image in PDF |
| PDF Text | `pdf-lib` | `embedFont(StandardFonts.Helvetica)`, `page.drawText()` |
| Text Editor | `@tiptap/vue-3` + extensions | `editor.getHTML()` / `editor.getText()` for export |
| Admin blog editor | Same TipTap instance | Full HTML editing, syncs to `form.content` |

---

## Color Palette (Brand)

```
Violet:  #7c3aed  (primary brand, buttons, active states)
Purple:  #9333ea
Pink:    #ec4899  (accent, gradients)
Orange:  #f97316  (PDF tools)
Amber:   #f59e0b
Teal:    #14b8a6  (PDF Sign)
Cyan:    #06b6d4
Green:   #22c55e  (Text Editor, success)
Blue:    #3b82f6  (PDF Text)
```

Each tool has a consistent color identity. Keep it.  
Gradient text: `gradient-text` CSS class (violet → pink → orange).  
Card hover glow: `tool-card-glow` CSS class + `--glow-color` CSS var.

---

## Ad Placement Convention

```
[ AdsTopBar ]        ← always above nav
[ Navigation ]
[ AdsSecondBar ]     ← always below nav
[ AdsLeft | content | AdsRight ]   ← xl screens only sidebars visible
[ AdsBottom ]
[ AdsPopup ]         ← triggered via provide/inject 'triggerAdPopup'
```

**Triggering popup:** In tool pages, inject `triggerAdPopup` and call it after a successful download action.

```js
const triggerAdPopup = inject('triggerAdPopup');
// After download:
triggerAdPopup();
```

**AdSense slot IDs** are placeholders (`ca-pub-XXXXXXXXXX`, `111...`). Replace in each `Ads/*.vue` file with real IDs.

---

## SEO Pattern

**Laravel side** — `ToolController` passes seo array:
```php
return Inertia::render('Tools/ImageCrop', [
    'seo' => config('seo.pages.image-crop'),
]);
```

All SEO data lives in `config/seo.php`.

**Vue side** — `AppLayout.vue` renders `<Head>` with title, description, keywords, og:image, twitter cards.

For blog posts, `Blog/Show.vue` additionally renders JSON-LD `Article` structured data.

---

## Admin Routes

```
GET  /admin/login           → Admin/Login.vue
POST /admin/login           → AdminAuthController@login
POST /admin/logout          → AdminAuthController@logout
GET  /admin                 → Admin/Dashboard.vue
GET  /admin/blog            → Admin/Blog/Index.vue
GET  /admin/blog/create     → Admin/Blog/Form.vue
POST /admin/blog            → store
GET  /admin/blog/{id}/edit  → Admin/Blog/Form.vue (isEdit=true)
PUT  /admin/blog/{id}       → update
DELETE /admin/blog/{id}     → destroy
```

---

## Database Tables

- `users` — default Laravel + `is_admin (boolean, default false)`
- `blog_posts` — title, slug (unique), content (longText HTML), excerpt, meta_title, meta_description, meta_keywords, og_image, is_published, published_at

---

## Docker Services (docker-compose.yml)

- `app` — Laravel app (php-fpm + nginx + supervisor), port 8080
- `db` — MySQL 8.0, port 3306, volume `db_data`
- `phpmyadmin` — phpMyAdmin, port 8081
- `vite` — Vite dev server, port 5173, profile `dev` only  
  Start with: `docker compose --profile dev up`

---

## Common Commands

```bash
# Local development
npm run dev                          # Vite dev server
php artisan serve                    # Laravel dev server
php artisan migrate                  # Run migrations
php artisan db:seed                  # Seed admin user
php artisan route:list               # List all routes

# Docker
docker compose up --build            # Production containers
docker compose --profile dev up      # With Vite dev server
docker compose exec app php artisan migrate --seed

# Generate app key (first setup)
php artisan key:generate
```

---

## Coding Conventions

- **Vue**: always `<script setup>` Composition API. No Options API in new files.
- **PHP**: PSR-12. Controller methods are short, delegate logic to models/services.
- **Tailwind**: utility-first. No custom CSS unless adding to `app.css` with a class comment.
- **Naming**: Vue pages match Inertia render string exactly (`'Tools/ImageCrop'` → `Pages/Tools/ImageCrop.vue`).
- **No Laravel Blade views** except `resources/views/app.blade.php` (Inertia root).
- **Form submissions** in admin use `router.post/put/delete` from `@inertiajs/vue3`.
- **Errors** from server are passed via Inertia error bag → stored in local `errors` ref.

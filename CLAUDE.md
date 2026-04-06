# CLAUDE.md — Project Context for gabrielaraducan-cv

## Overview

Personal CV/resume website for **Gabriela Raducan**, a Senior PHP Developer / Contractor / Freelancer with 15+ years of experience, based in Bucharest, Romania (remote only). The site currently displays her resume at the root URL, matching the design of the original PDF resume created by a designer.

**GitHub:** `gabrielaraducanfreelancer/gabrielaraducan-cv`

## Tech Stack

| Layer        | Technology                         |
|--------------|------------------------------------|
| Framework    | Laravel 13 (PHP ^8.3)             |
| Admin Panel  | Filament v5                        |
| Database     | MySQL 8.4 (via Docker)            |
| Frontend     | Blade templates, Tailwind CSS 4, Vite 8 |
| Local Dev    | Docker via Laravel Sail            |
| Font         | Google Fonts — Open Sans (300, 400, 600, 700) |

**Important:** PHP and Composer are NOT installed natively on the Mac. All PHP/Composer/Artisan commands must be run via Docker:
- Composer commands: `/usr/local/bin/docker run --rm -v "$(pwd)":/app -w /app composer:latest <command>`
- Node/npm commands: `/usr/local/bin/docker run --rm -v "$(pwd)":/app -w /app node:22-alpine <command>`
- When Sail is running: `./vendor/bin/sail artisan <command>`, `./vendor/bin/sail composer <command>`, `./vendor/bin/sail npm <command>`

## How to Run Locally

```bash
cd ~/gabrielaraducan-cv
./vendor/bin/sail up -d              # starts Laravel + MySQL containers
./vendor/bin/sail artisan migrate    # run migrations
./vendor/bin/sail npm install        # install JS dependencies
./vendor/bin/sail npm run dev        # Vite dev server with hot reload
```
Then visit `http://localhost`. Filament admin panel at `http://localhost/admin`.

## Original Mockup / Design Source

The original CV files from the designer are stored in `docs/mockups/`:
- `docs/mockups/Gabriela Raducan CV long.pdf` — the PDF resume (3 pages), used as the design reference
- `docs/mockups/Gabriela Raducan CV long.ai` — the Adobe Illustrator source file

The profile photo was extracted from the PDF and is stored at `public/images/profile.jpg` (312x416px JPEG).

## Design System

### Colors (CSS custom properties in `resume.blade.php`)
| Variable           | Value       | Usage                                         |
|--------------------|-------------|-----------------------------------------------|
| `--accent`         | `#F0A500`   | Orange — links, dates, company names, bullets |
| `--sidebar-bg`     | `#3D3D3D`   | Dark gray — sidebar background                |
| `--sidebar-text`   | `#ffffff`   | White — all sidebar text                      |
| `--main-bg`        | `#ffffff`   | White — main content background               |
| `--main-text`      | `#333333`   | Dark gray — headings, body text               |
| `--light-gray`     | `#e0e0e0`   | Light gray — reserved for borders/dividers    |
| body background    | `#f0f0f0`   | Light gray — page background behind the CV    |

### Typography
- **Font family:** Open Sans (loaded from Google Fonts)
- **Name "Gabriela":** bold (700), "Raducan": light (300), 42px
- **Section titles:** 16px, uppercase, bold, letter-spacing 1px
- **Sidebar headings:** 14px, uppercase, bold, letter-spacing 1.5px
- **Body text:** 13-13.5px
- **Experience role:** 15px bold
- **Dates & company names:** 13px, orange (`--accent`), font-weight 600

### Layout
- **Two-column layout** inside a max-width 1000px centered container with box-shadow
- **Left sidebar:** 320px fixed width, dark gray background
  - Circular profile photo (200x200px, border-radius 50%)
  - Contact section with SVG icons (phone, email, location, LinkedIn, web)
  - Skills list (name + years, right-aligned)
  - Education section
  - Sections separated by `<hr>` dividers (white, 25% opacity)
- **Right main content:** flexible width, 45px padding
  - Name header + subtitle
  - "About Me" section with orange left border (3px)
  - Work Experience entries with: role + dates header, company name, description, bullet highlights (orange `►` markers), skills list
- **Responsive:** stacks vertically below 768px
- **Print-friendly:** removes box-shadow, full width

### Bullet Style
Experience highlights use the `►` character (unicode `\25BA`) in orange as list markers, matching the original PDF design.

## Folder Structure

```
gabrielaraducan-cv/
├── app/
│   ├── Http/Controllers/
│   │   ├── Controller.php              # Base controller
│   │   └── ResumeController.php        # Invokable — renders the resume page
│   ├── Models/
│   │   └── User.php                    # Default Laravel user model
│   └── Providers/
│       ├── AppServiceProvider.php       # Default app service provider
│       └── Filament/
│           └── AdminPanelProvider.php   # Filament admin panel config (/admin)
├── bootstrap/
│   ├── app.php                         # App bootstrapping
│   └── providers.php                   # Service provider registration
├── config/                             # Laravel configuration files
├── database/
│   ├── factories/                      # Model factories
│   ├── migrations/                     # DB migrations (users, cache, jobs)
│   └── seeders/                        # Database seeders
├── docs/
│   └── mockups/                        # Original CV design files (PDF + AI)
├── public/
│   ├── images/
│   │   └── profile.jpg                 # Gabriela's profile photo (extracted from PDF)
│   ├── build/                          # Vite compiled assets (gitignored)
│   ├── css/filament/                   # Filament published CSS
│   ├── js/filament/                    # Filament published JS
│   ├── fonts/filament/                 # Filament Inter font files
│   └── index.php                       # Application entry point
├── resources/
│   ├── css/
│   │   └── app.css                     # Tailwind CSS entry point
│   ├── js/
│   │   ├── app.js                      # Main JS entry point
│   │   └── bootstrap.js                # Axios setup
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Base HTML layout (@yield content, @stack styles)
│       ├── resume.blade.php            # Main resume page (all content + CSS)
│       └── welcome.blade.php           # Default Laravel welcome (unused)
├── routes/
│   ├── web.php                         # Web routes — "/" → ResumeController
│   └── console.php                     # Console/artisan routes
├── tests/                              # PHPUnit tests
├── compose.yaml                        # Docker Compose — Laravel Sail + MySQL 8.4
├── composer.json                       # PHP dependencies
├── package.json                        # JS dependencies (Vite, Tailwind)
├── vite.config.js                      # Vite config with Laravel plugin + Tailwind
├── phpunit.xml                         # PHPUnit configuration
└── CLAUDE.md                           # This file
```

## Key Files

| File | Purpose |
|------|---------|
| `routes/web.php` | Single route: `GET /` → `ResumeController` |
| `app/Http/Controllers/ResumeController.php` | Invokable controller, returns `resume` view |
| `resources/views/layouts/app.blade.php` | Base HTML layout with `@yield('content')` and `@stack('styles')` — designed for easy page additions |
| `resources/views/resume.blade.php` | The full resume page — contains all CV content and all CSS in a `@push('styles')` block |
| `app/Providers/Filament/AdminPanelProvider.php` | Filament admin at `/admin`, uses Amber as primary color, has login gate |
| `compose.yaml` | Docker services: `laravel.test` (PHP 8.5 Sail image, port 80) + `mysql` (MySQL 8.4, port 3306/3307) |
| `public/images/profile.jpg` | Profile photo, 312x416px JPEG |

## Resume Content

The resume includes 11 work experience entries spanning from 2011 to present:

1. **Powercloud (Germany)** — Sept 2021 - Present — Senior PHP Developer
2. **medicplan.ro (Romania)** — Mar 2021 - Aug 2021 — Senior PHP Developer
3. **Jobshark (Romania)** — Oct 2020 - Mar 2021 — Senior PHP Developer
4. **Hellorider (Netherlands)** — Apr 2020 - Sept 2020 — Senior PHP Developer
5. **Fietsenwinkel (Netherlands)** — Feb 2019 - Mar 2020 — Senior PHP Developer
6. **Ticketscript (Netherlands)** — Oct 2014 - Mar 2017 — Senior PHP Developer
7. **Cloudwalkers (Denmark)** — Mar 2014 - Sept 2014 — PHP Developer
8. **Timessnewroman.ro (Romania)** — Dec 2013 - Feb 2014 — PHP Developer
9. **Wind River Systems (US-RO)** — Jul 2013 - Dec 2013 — PHP Developer
10. **WindRiver (US-RO)** — Mar 2011 - May 2013 — PHP Developer

Skills years in the sidebar have been updated from the original PDF values to reflect current experience (e.g., PHP from "11 years" → "15+ years").

**Note:** The resume needs to be brought up to date with the latest projects Gabriela has worked on beyond Powercloud.

## Architecture Decisions

- **Blade templates with `@extends`/`@yield`:** chosen over Livewire/single-page to keep things simple and make it easy to add new pages with a menu later
- **CSS in `@push('styles')`:** all resume styles are scoped inside the resume view rather than in a global CSS file, so adding new pages won't conflict
- **CSS custom properties:** colors defined as `--accent`, `--sidebar-bg`, etc. for easy theming
- **Invokable controller:** `ResumeController` uses `__invoke()` since it only handles one action
- **Filament admin panel:** pre-installed at `/admin` with login gate, ready for future content management (e.g., managing resume entries via database)
- **No database usage yet:** resume content is hardcoded in the Blade template. Future iteration could move this to database models managed via Filament

## Future Plans

- Add a navigation menu to the site
- Move the resume to a sub-page/route
- Add new sections: portfolio, blog, or other professional content
- Potentially move resume data into database models with Filament CRUD
- Bring resume content up to date with latest work experience

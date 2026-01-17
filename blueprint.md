# UNIVERSITY CMS DELTA BLUEPRINT (Delta Plan + Progress Tracker)
Project: Laravel (Metronic Admin) + Next.js University CMS
Date Started: 2026-01-17
Goal: Match exact route map + proper CMS/page builder for Main + Faculty + Department + Offices
Status: IN PROGRESS

---

## 0) Current Baseline (What Already Exists ✅)
### Laravel (confirmed from uploaded zips)
- Academic CMS tables exist:
  - academic_sites, academic_departments
  - academic_pages (has owner_type, owner_id, template_key, settings)
  - academic_nav_items (already owner-aware via 2026_01_13_* migration)
  - academic_page_blocks (already exists)
- Notices + categories + attachments exist:
  - notices, notice_categories
- News exists:
  - news
- Administration office system exists:
  - admin_offices, admin_office_sections, admin_office_members
- Public API exists (api.php):
  - cms/main, cms/site/{siteSlug}, cms/department/{departmentSlug}
  - notices, notices/{slug}, news, news/{slug}
  - administration/office/{slug}

### Next.js (confirmed)
- Main routes exist: home, notices, news, admin office pages
- Faculty routes exist under /(faculties)/[slug]
- Faculty/department resolver currently uses an ambiguous segment approach.

---

## 1) Target Route Map (Must Match Exactly ✅)

## Global Navigation Requirements (KAU-style Topbar + Main Navbar)
- There are **2 header menu locations**:
  1) **Topbar menu** (right side like screenshot: *Notice | News*).
     - **No child menus** allowed (single level).
     - Typically used for quick links (Notice, News, Tender, Events, Contact, Login).
  2) **Main navbar menu** (Home/About/Academic/Office & Administration/Admission etc).
     - Supports unlimited child menus (dropdowns).

**Implementation plan (DB + Admin UI) — to be done in Step A4 / Step C2:**
- Add `menu_location` to navigation items (enum: `topbar`, `navbar`, default `navbar`).
- Enforce rule: if `menu_location=topbar`, then `parent_id` must be NULL.

## Gallery Requirement (Image Gallery + Video Gallery under About)
- Galleries must be available for **Main site**, **Faculty**, and **Department** pages.
- The menu link for Gallery must live under **About** in the main navbar.

**Which builders will be used:**
- **Album Builder (recommended):** dedicated CMS module:
  - `galleries` (album) + `gallery_items` (images/videos) with `owner_type/owner_id` like pages.
  - Admin can create album, upload images, paste video URLs, order items, choose cover image.
- **Page Builder Integration:** a page block type `gallery_album` to embed an album inside any CMS page (e.g., About → Gallery page).


- / (Home blocks + featured notices/news/events)
- /page/[slug]
- /notices + /notices/[category] + /notice/[slug]
- /news + /news/[slug]
- /events + /events/[slug]
- /tender + /tender/[slug]
- /[facultySlug]
- /[facultySlug]/[facultyPageSlug]
- /[facultySlug]/[departmentSlug]
- /[facultySlug]/[departmentSlug]/[departmentPageSlug]
- /[facultySlug]/[departmentSlug]/[memberUuid]
- /offices/[slug]
- /offices/[slug]/[pageSlug]
- /dynamicSlug/[uuid]
- /search?q=

---

# PHASE A — DATABASE & MODELS (Safe changes first)

## Step A1 — Add Events & Tenders tables (New modules) ✅ COMPLETED
### Why
You currently have notices + news. Events & tender are missing but required by route map.

### Create migrations
Create new migration files:
- database/migrations/2026_01_17_000001_create_events_table.php
- database/migrations/2026_01_17_000002_create_tenders_table.php

### Create models
- app/Models/Event.php
- app/Models/Tender.php

### Create admin controllers
- app/Http/Controllers/Admin/EventController.php
- app/Http/Controllers/Admin/TenderController.php

### Create admin views (Metronic pattern)
- resources/views/admin/pages/events/index.blade.php
- resources/views/admin/pages/events/create.blade.php
- resources/views/admin/pages/events/edit.blade.php
- resources/views/admin/pages/tenders/index.blade.php
- resources/views/admin/pages/tenders/create.blade.php
- resources/views/admin/pages/tenders/edit.blade.php

### DONE criteria
- Admin can create/edit/publish events + tenders with attachments
- Public API can list + detail them (Step B2)

### Snapshot (what was added in this step)
- **Migrations:**
  - `database/migrations/2026_01_17_000001_create_events_table.php`
  - `database/migrations/2026_01_17_000002_create_tenders_table.php`
- **Models:**
  - `app/Models/Event.php`
  - `app/Models/Tender.php`
- **Admin controllers (Metronic pattern, no modals):**
  - `app/Http/Controllers/Admin/EventController.php`
  - `app/Http/Controllers/Admin/TenderController.php`
- **Admin views:**
  - `resources/views/admin/pages/events/*`
  - `resources/views/admin/pages/tenders/*`

### Notes
- Permissions used (add to your permission seeder/DB if not already):
  - `view events`, `create events`, `edit events`, `delete events`
  - `view tenders`, `create tenders`, `edit tenders`, `delete tenders`
- Routes to add later (Step C / your routes file):
  - `admin.events.*` and `admin.tenders.*` (index/create/store/edit/update/destroy + toggleFeatured + toggleStatus)


Progress: [x] Done
Notes:
- 

---

## Step A2 — Add ownership to notices/news/events/tenders (Main/Faculty/Dept/Office)
### Why
To support JUST/KUET/DUET-like behavior where faculty/offices may have their own notices/news.

### Edit migrations (new migration, NOT editing old files)
Create migration:
- database/migrations/2026_01_17_000010_add_owner_to_posts_tables.php

Add columns:
- notices: owner_type (default 'main'), owner_id nullable
- news: owner_type (default 'main'), owner_id nullable
- events: owner_type (default 'main'), owner_id nullable
- tenders: owner_type (default 'main'), owner_id nullable

Add indexes:
- (owner_type, owner_id, status, published_at)
- (slug) unique per type if required, or (owner_type, owner_id, slug)

### Update models
- Notice.php: add fillable + casts if needed
- News.php: add fillable + casts
- Event.php / Tender.php: include owner fields

### DONE criteria
- Existing data still loads
- Admin can optionally set owner scope (later UI step)

Progress: [x] Done
Notes:
- 

---

## Step A3 — Add Search Index table (Optional but recommended for /search)
Create migration:
- database/migrations/2026_01_17_000020_create_search_index_table.php

Create model:
- app/Models/SearchIndex.php

Create job/command (later)
- app/Console/Commands/RebuildSearchIndex.php
- app/Jobs/IndexPublishedContentJob.php

DONE criteria:
- Table exists and can be filled (later via step B3)

### Snapshot (what was added in this step)
- **Migration:** `database/migrations/2026_01_17_000020_create_search_index_table.php`
- **Model:** `app/Models/SearchIndex.php`
- **Command:** `app/Console/Commands/RebuildSearchIndex.php` (rebuild full index)
- **Job:** `app/Jobs/IndexPublishedContentJob.php` (incremental indexing hook; will be wired during API publish steps)

Progress: [x] Done
Notes:
- 

---

# PHASE B — API UPDATES (Match frontend route map)

## Step B1 — Add CMS bundle endpoint for offices
### Why
Your CMS bundle exists for main/site/department. Offices must be first-class.

### Edit file
- app/Http/Controllers/Frontend/Api/CmsBundleController.php
Add method:
- office($officeSlug)

### Add route
Edit: routes/api.php
Add:
- GET /api/v1/cms/office/{officeSlug}

### Data returned (same bundle pattern)
- office data
- navigation (owner_type='office', owner_id=office_id)
- pages (owner_type='office', owner_id=office_id)
- featured posts (optional later)

DONE criteria:
- /api/v1/cms/office/{slug} returns consistent JSON like site/department

Progress: [x] Done
Notes:
- 

---

## Step B2 — Make public endpoints match exact map
### Required changes in routes/api.php
You currently have:
- /notices
- /notices/{slug}  (this conflicts with needing /notices/[category] AND /notice/[slug])

Fix by implementing:
- GET /api/v1/notices?category=categorySlug&page=...
- GET /api/v1/notice/{slug}

Keep old route temporarily but plan to deprecate:
- GET /api/v1/notices/{slug} -> redirect logic or keep until Next.js is migrated

Add for new modules:
- GET /api/v1/events
- GET /api/v1/events/{slug}
- GET /api/v1/tenders
- GET /api/v1/tender/{slug}

### Edit controller
- app/Http/Controllers/Frontend/Api/HomeApiController.php
Add/adjust methods:
- allNotices($request): accept category slug filter
- noticeDetailsBySlug($slug): for /notice/{slug}
- allEvents(), eventDetails($slug)
- allTenders(), tenderDetails($slug)

DONE criteria:
- All required endpoints exist and return stable structure
- Next.js can switch without breaking

Progress: [x] Done
Notes:
- 

---

## Step B3 — Add /search endpoint
### Add route
- GET /api/v1/search?q=

### Implement controller method
Option 1 (Fast MVP): query across published notices/news/pages by LIKE
Option 2 (Better): use search_index table (Step A3)

Recommended:
- Start with MVP LIKE search, then upgrade to index table.

DONE criteria:
- /search returns results with URL + title + snippet + type

Progress: [x] Done
Notes:
- 

---

# PHASE C — NEXT.JS ROUTING RESTRUCTURE (Remove ambiguity + match exact routes)

## Step C1 — Replace ambiguous faculty segment resolver with explicit folders
### Why
Your current /(faculties)/[slug]/[segment] strategy becomes wrong once faculty page slug collides with department slug.
Your required map needs explicit nested routing.

### Create/Move Next.js routes
Create these pages (and remove the old ambiguous resolver page when ready):
- src/app/(faculties)/[facultySlug]/page.tsx
- src/app/(faculties)/[facultySlug]/[facultyPageSlug]/page.tsx
- src/app/(faculties)/[facultySlug]/[departmentSlug]/page.tsx
- src/app/(faculties)/[facultySlug]/[departmentSlug]/[departmentPageSlug]/page.tsx
- src/app/(faculties)/[facultySlug]/[departmentSlug]/[memberUuid]/page.tsx

### Update data fetching
- Faculty bundle: GET /api/v1/cms/site/{facultySlug}
- Department bundle: either:
  - GET /api/v1/cms/department/{departmentSlug} (current)
  - OR (more correct) new endpoint: /api/v1/cms/site/{facultySlug}/department/{departmentSlug}
    (Optional in later step)

DONE criteria:
- Faculty and department pages render without the segment resolver
- Menu components still work without hook order issues

Progress: [x] Done
Notes:
- 

---

## Step C2 — Add notices category route + single notice detail route
### Create Next.js pages
- src/app/(main)/notices/[category]/page.tsx
- src/app/(main)/notice/[slug]/page.tsx

### Update existing notices list/detail components
- Notice list should link to /notice/[slug]
- Category list should use /notices/[category]

DONE criteria:
- /notices/categorySlug works
- /notice/noticeSlug works

Progress: [x] Done
Notes:
- 

---

## Step C3 — Add events + tender routes
Create:
- src/app/(main)/events/page.tsx
- src/app/(main)/events/[slug]/page.tsx
- src/app/(main)/tender/page.tsx
- src/app/(main)/tender/[slug]/page.tsx

DONE criteria:
- routes render and show data from APIs

Progress: [x] Done
Notes:
- 

---

## Step C4 — Offices routes (match required /offices)
### Create
- src/app/(main)/offices/[slug]/page.tsx
- src/app/(main)/offices/[slug]/[pageSlug]/page.tsx

### Use new CMS bundle office API
- GET /api/v1/cms/office/{slug}

DONE criteria:
- office main page works at /offices/{slug}
- office other pages work at /offices/{slug}/{pageSlug}

Progress: [x] Done
Notes:
- 

---

## Step C5 — /page/[slug], /dynamicSlug/[uuid], /search
Create:
- src/app/(main)/page/[slug]/page.tsx
- src/app/(main)/dynamicSlug/[uuid]/page.tsx
- src/app/(main)/search/page.tsx

DONE criteria:
- Dynamic page renders from main CMS pages
- Member uuid page renders from staff/member API
- Search returns results

Progress: [x] Done
Notes:
- 

---

# PHASE D — ADMIN UI REWORK (Metronic-based, no modals, organized page builder)

## Step D1 — Academic Pages UI: split index/create/edit into separate pages
### Why
You currently have list + form on same page (pages.blade.php). Admin asked for cleaner “page builder” experience.
Also you want hints to explain which field controls which frontend part.

### Create new routes (admin.php)
- admin.academic.pages.index
- admin.academic.pages.create
- admin.academic.pages.edit

### Controller changes
Edit:
- app/Http/Controllers/Admin/AcademicContentController.php
Refactor methods (no logic change, only split):
- index() -> only list
- create() -> create form page
- store()
- edit($id) -> edit form page
- update($id)
- destroy($id)

### View changes (Metronic style)
Create:
- resources/views/admin/pages/academic/pages/index.blade.php
- resources/views/admin/pages/academic/pages/create.blade.php
- resources/views/admin/pages/academic/pages/edit.blade.php

Move existing form partial to:
- resources/views/admin/pages/academic/pages/_form.blade.php

Add hint blocks:
- “This title appears as page heading”
- “Slug controls URL”
- “template_key selects frontend template”
- “blocks control the sections visible on frontend”

DONE criteria:
- Admin can manage pages without mixed list+edit confusion
- No modals used for page builder

Progress: [x] Done
Notes:
- 

---

## Step D2 — Build a proper Page Builder UI using academic_page_blocks
### Why
You already have academic_page_blocks table, but admin forms are not yet “builder-friendly”.

### Implement builder flow
On page edit screen:
- Left: Page meta (title/slug/template/status)
- Right: Blocks manager:
  - Add block (select type)
  - Reorder blocks (sortable)
  - Each block opens inline editor panel (not modal)
  - Save persists into academic_page_blocks (settings json)

### Files
Controller:
- AcademicContentController.php (or a new AcademicPageBuilderController.php)
Views:
- resources/views/admin/pages/academic/pages/edit.blade.php
- resources/views/admin/pages/academic/pages/partials/blocks.blade.php
JS:
- resources/views/admin/pages/academic/pages/partials/builder_js.blade.php
Use your existing Metronic components:
- editor, image-input, color-picker components in metronic folder (DO NOT change those)

DONE criteria:
- Builder blocks can be created/sorted/updated
- Frontend reads blocks and renders correctly (later Step E)

Progress: [x] Done
Notes:
- 

---

## Step D3 — Office module: convert office sections/pages to unified CMS builder
### Current problem
Office page is accordion of “sections”, and staff management is mixed into it. Admin experience is confusing.

### New office admin structure (clean)
- Offices list
- Office dashboard (overview + quick links)
- Office pages (CMS pages + menu)
- Office members directory (staff)
- Optional: office homepage blocks

### Implementation options
Option 1 (recommended): Use academic_pages + academic_nav_items for office too
- owner_type='office', owner_id=admin_offices.id
- Office “members” remain in admin_office_members OR merge to academic_staff_members later.

### Concrete changes
Routes (admin.php):
- admin.offices.pages.index/create/edit  (new)
- admin.offices.menu.index              (new)
- admin.offices.members.index/create/edit (existing staff forms can be reorganized)

Controllers:
- Add OfficeCmsController.php (recommended)
Views:
- resources/views/admin/pages/offices/pages/*
- resources/views/admin/pages/offices/menu/*
- resources/views/admin/pages/offices/members/*

UI rule:
- No modals for office pages; full page create/edit forms.

DONE criteria:
- Office admin is no longer a confusing accordion-only editor
- Office pages + menu builder are consistent with faculty/dept

Progress: [x] Done
Notes:
- 

---

# PHASE E — FRONTEND RENDERING (Templates + blocks)

## Step E1 — Standardize CMS response format (main/site/department/office)
Ensure cms bundle always returns:
- owner (site/department/office/main)
- navigation (tree)
- pages list
- selected page (when needed)
- blocks (if page blocks exist)
- featured posts (optional)

DONE criteria:
- Next.js components can render from one common structure

Progress: [x] Done
Notes:
- 

---

## Step E2 — Render templates by template_key
Create frontend template registry:
- templates/page/default
- templates/page/withSidebar
- templates/page/departmentHome
- templates/page/facultyMembers
etc.

DONE criteria:
- template_key changes output without breaking layout

Progress: [x] Done
Notes:
- 

---

# PHASE F — CLEANUP & MIGRATION SAFETY

## Step F1 — Keep old endpoints temporarily, then deprecate
- Keep /api/v1/notices/{slug} during Next.js switch
- Once Next.js uses /notice/{slug}, remove/deprecate

Progress: [x] Done
Notes:
- 

---

# 2) Overall Completion Tracker
Phase A (DB/Models): 0% 
Phase B (API): 0%
Phase C (Next Routes): 0%
Phase D (Admin UX): 0%
Phase E (Frontend Templates/Blocks): 0%
Phase F (Cleanup): 0%

---

# 3) Changelog (Append-only)
- 2026-01-17: Delta blueprint created.

---

NEXT STEP: Step A4 — Add Gallery module + header menu locations (Topbar/Navbar)

---

## Step A4 — Add Gallery module + header menu locations (Topbar/Navbar)
### Why
- You need Image Gallery + Video Gallery under About for Main/Faculty/Department.
- You need two header menu locations: Topbar (no child) + Main Navbar (supports dropdowns).

### DB changes (new migrations)
1) Navigation:
- add `menu_location` to navigation items (enum: `topbar`, `navbar`, default `navbar`)
- enforce rule: topbar items have `parent_id` = NULL (validated in Admin + API)

2) Galleries:
- galleries (albums): owner_type/owner_id, title, slug, cover_media_id, status, position
- gallery_items: gallery_id, type (`image`|`video`), media_id nullable, video_url nullable, title, position

### Admin UI
- Gallery Album CRUD (separate pages, no modals)
- Gallery Items manager (sortable, upload images, paste video URL)
- Menu Builder UI: location selector (topbar/navbar), and topbar disables nesting

Progress: [ ] Not Started
Notes:
-


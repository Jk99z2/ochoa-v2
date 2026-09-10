# Graph Report - ochoa-v2  (2026-09-09)

## Corpus Check
- Large corpus: 207 files · ~730,079 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 713 nodes · 1192 edges · 65 communities (34 shown, 11 thin omitted)
- Extraction: 94% EXTRACTED · 6% INFERRED · 0% AMBIGUOUS · INFERRED: 67 edges (avg confidence: 0.86)
- Token cost: 83,612 input · 0 output

## Community Hubs (Navigation)
- jQuery Vendor Bundle
- Composer Dependencies
- Filament Widgets & Models
- Database Migrations
- jQuery Core Library
- Filament Panel & Auth
- Filament Table Actions
- Docker Compose Services
- Filament Resource List Pages
- Popper.js Library
- Configuracion Model & Providers
- AOS Animation Library
- Agente Resource CRUD
- Frontend Build Tooling
- Amenidad & Lead Resources
- Lead & Propiedad Forms
- Filament Form Components
- Propiedad View & Edit Pages
- User & Propiedad Create Pages
- Scrollax Animation Library
- jQuery Selector Engine
- jQuery Ajax & Animation Internals
- Configuracion Admin Page
- Flaticon Icon Assets
- Bootstrap JS Library
- jQuery DOM Manipulation
- Bootstrap Datepicker
- Feature Test Suite
- Logging Configuration
- Unit Test Suite
- jQuery Migrate Shim
- Waypoints Scroll Library
- Propiedades Index View
- Propiedades Show View
- Welcome Page View
- Console Routes
- jQuery CSS Dimension Helpers
- jQuery Promise Internals
- Owl Carousel Library
- Production Deploy Script
- Staging Deploy Script
- Staging Refresh Script
- Base HTTP Controller
- jQuery Cache Helpers
- jQuery Prefilter Inspection

## God Nodes (most connected - your core abstractions)
1. `UserResource` - 18 edges
2. `n()` - 17 edges
3. `PropiedadResource` - 16 edges
4. `Ochoa Real Estate Services Platform` - 15 edges
5. `Propiedad` - 14 edges
6. `User` - 13 edges
7. `v()` - 13 edges
8. `b()` - 12 edges
9. `Configuracion` - 11 edges
10. `LeadResource` - 11 edges

## Surprising Connections (you probably didn't know these)
- `Ochoa Real Estate Services Platform` --references--> `robots.txt (Allow All Crawlers)`  [INFERRED]
  README.md → src/public/robots.txt
- `app service (ochoa2_app, local dev)` --conceptually_related_to--> `app service (ochoa2_app, production)`  [INFERRED]
  docker-compose.yml → docker-compose.prod.yml
- `web service (ochoa2_web, local dev)` --conceptually_related_to--> `web service (ochoa2_web, production)`  [INFERRED]
  docker-compose.yml → docker-compose.prod.yml
- `db service (ochoa2_db, local dev)` --conceptually_related_to--> `db service (ochoa2_db, production)`  [INFERRED]
  docker-compose.yml → docker-compose.prod.yml
- `Flaticon WebFont Demo Page` --cites--> `Flaticon Free License (With Attribution)`  [EXTRACTED]
  src/public/fonts/flaticon/font/flaticon.html → src/public/fonts/flaticon/license/license.pdf

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Flaticon Icon Font Asset Bundle** — src_public_fonts_flaticon_backup_icon_collection, src_public_fonts_flaticon_font_flaticon_webfont, src_public_fonts_flaticon_font_flaticon_pin, src_public_fonts_flaticon_font_flaticon_detective, src_public_fonts_flaticon_font_flaticon_house, src_public_fonts_flaticon_font_flaticon_purse, src_public_fonts_flaticon_font_flaticon_bed, src_public_fonts_flaticon_font_flaticon_bathtub, src_public_fonts_flaticon_font_flaticon_selection, src_public_fonts_flaticon_license_license_flaticon_free_license [INFERRED 0.85]
- **Local Dev Docker Compose Stack (app+web+db)** — docker_compose_app, docker_compose_web, docker_compose_db [EXTRACTED 1.00]
- **Known Deployment Gotchas (Docker/Nginx/Filament Pitfalls)** — readme_fastcgi_pass_container_naming, readme_config_cache_bug, readme_filamentuser_contract, readme_trustproxies_middleware, readme_fileupload_disk_public, readme_opcache_settings, readme_shared_nginx_conf_bug, readme_nginx_conf_mount_error [INFERRED 0.85]

## Communities (65 total, 11 thin omitted)

### Community 0 - "jQuery Vendor Bundle"
Cohesion: 0.07
Nodes (32): A(), at(), b(), be(), ce(), e(), Ee(), fe() (+24 more)

### Community 1 - "Composer Dependencies"
Cohesion: 0.04
Nodes (48): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+40 more)

### Community 2 - "Filament Widgets & Models"
Cohesion: 0.08
Nodes (23): Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat, Filament\Widgets\TableWidget, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\BelongsToMany, Illuminate\Database\Eloquent\Relations\HasMany (+15 more)

### Community 3 - "Database Migrations"
Cohesion: 0.08
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 4 - "jQuery Core Library"
Cohesion: 0.05
Nodes (8): dataAttr(), finalPropName(), getData(), NOTE: This can be skipped if there are no unmatched elements (i.e.,…, TODO: Now that all calls to _data and _removeData have been replaced, TODO: identify versions, TODO: identify versions, vendorPropName()

### Community 5 - "Filament Panel & Auth"
Cohesion: 0.07
Nodes (25): Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Models\Contracts\FilamentUser, Filament\Pages\Dashboard, Filament\Panel, Filament\PanelProvider (+17 more)

### Community 6 - "Filament Table Actions"
Cohesion: 0.11
Nodes (16): Filament\Actions\BulkActionGroup, Filament\Actions\EditAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreBulkAction, Filament\Resources\RelationManagers\RelationManager, Filament\Tables\Columns\IconColumn, Filament\Tables\Columns\ImageColumn, Filament\Tables\Columns\TextColumn (+8 more)

### Community 7 - "Docker Compose Services"
Cohesion: 0.09
Nodes (29): app service (ochoa2_app, local dev), db service (ochoa2_db, local dev), app service (ochoa2_app, production), db service (ochoa2_db, production), .env.prod (compose-level env file), proxy network (external, Nginx Proxy Manager), web service (ochoa2_web, production), web service (ochoa2_web, local dev) (+21 more)

### Community 8 - "Filament Resource List Pages"
Cohesion: 0.10
Nodes (10): Filament\Actions\CreateAction, Filament\Resources\Pages\ListRecords, Filament\Resources\Pages\ManageRecords, ListAgentes, AmenidadResource, ManageAmenidads, ListLeads, ListPropiedades (+2 more)

### Community 9 - "Popper.js Library"
Cohesion: 0.27
Nodes (22): a(), b(), c(), d(), e(), f(), g(), h() (+14 more)

### Community 10 - "Configuracion Model & Providers"
Cohesion: 0.11
Nodes (10): Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\View, Illuminate\Support\ServiceProvider, Illuminate\Support\Str, Pdo\Mysql, self, Configuracion, AppServiceProvider (+2 more)

### Community 11 - "AOS Animation Library"
Cohesion: 0.30
Nodes (19): a(), e(), i(), n(), c(), d(), f(), i() (+11 more)

### Community 12 - "Agente Resource CRUD"
Cohesion: 0.15
Nodes (8): Filament\Actions\DeleteAction, Filament\Resources\Pages\EditRecord, AgenteResource, CreateAgente, EditAgente, AgenteForm, EditLead, EditUser

### Community 13 - "Frontend Build Tooling"
Cohesion: 0.12
Nodes (17): concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, devDependencies, concurrently, laravel-vite-plugin (+9 more)

### Community 14 - "Amenidad & Lead Resources"
Cohesion: 0.20
Nodes (8): BackedEnum, Filament\Actions\DeleteBulkAction, Filament\Resources\Resource, Filament\Support\Icons\Heroicon, Illuminate\Database\Eloquent\Builder, LeadResource, CreateLead, TipoResource

### Community 15 - "Lead & Propiedad Forms"
Cohesion: 0.17
Nodes (6): Filament\Schemas\Schema, Illuminate\Database\Eloquent\SoftDeletingScope, LeadForm, PropiedadForm, PropiedadInfolist, UserForm

### Community 16 - "Filament Form Components"
Cohesion: 0.20
Nodes (11): Filament\Actions\Action, Filament\Forms\Components\CheckboxList, Filament\Forms\Components\FileUpload, Filament\Forms\Components\Select, Filament\Forms\Components\Textarea, Filament\Forms\Components\TextInput, Filament\Forms\Components\Toggle, Filament\Notifications\Notification (+3 more)

### Community 17 - "Propiedad View & Edit Pages"
Cohesion: 0.15
Nodes (7): Filament\Actions\ForceDeleteAction, Filament\Actions\RestoreAction, Filament\Actions\ViewAction, Filament\Resources\Pages\ViewRecord, EditPropiedad, ViewPropiedad, PropiedadResource

### Community 18 - "User & Propiedad Create Pages"
Cohesion: 0.15
Nodes (4): Filament\Resources\Pages\CreateRecord, CreatePropiedad, CreateUser, UserResource

### Community 19 - "Scrollax Animation Library"
Cohesion: 0.18
Nodes (9): G(), a(), b(), c(), d(), k(), ka(), l() (+1 more)

### Community 20 - "jQuery Selector Engine"
Cohesion: 0.20
Nodes (12): addCombinator(), condense(), createPositionalPseudo(), elementMatcher(), markFunction(), matcherFromGroupMatchers(), matcherFromTokens(), multipleContexts() (+4 more)

### Community 21 - "jQuery Ajax & Animation Internals"
Cohesion: 0.18
Nodes (12): adoptValue(), ajaxConvert(), ajaxHandleResponses(), Animation(), createFxNow(), createTween(), defaultPrefilter(), done() (+4 more)

### Community 23 - "Flaticon Icon Assets"
Cohesion: 0.33
Nodes (10): My Icons Collection (Flaticon Backup), .flaticon-bathtub Icon (Smashicons), .flaticon-bed Icon (smalllikeart), .flaticon-detective Icon (Freepik), .flaticon-house Icon (Freepik), .flaticon-pin Icon (Nikita Golubev), .flaticon-purse Icon (Freepik), .flaticon-selection Icon (Freepik) (+2 more)

### Community 24 - "Bootstrap JS Library"
Cohesion: 0.24
Nodes (3): i(), o(), t()

### Community 25 - "jQuery DOM Manipulation"
Cohesion: 0.24
Nodes (10): buildFragment(), disableScript(), DOMEval(), domManip(), getAll(), manipulationTarget(), nodeName(), remove() (+2 more)

### Community 26 - "Bootstrap Datepicker"
Cohesion: 0.29
Nodes (3): opts_from_el(), UTCDate(), UTCToday()

### Community 27 - "Feature Test Suite"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 28 - "Logging Configuration"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 30 - "jQuery Migrate Shim"
Cohesion: 0.83
Nodes (3): a(), n(), r()

### Community 32 - "Propiedades Index View"
Cohesion: 0.50
Nodes (3): partials.footer, partials.nav, partials.nav-script

### Community 33 - "Propiedades Show View"
Cohesion: 0.50
Nodes (3): partials.footer, partials.nav, partials.nav-script

### Community 34 - "Welcome Page View"
Cohesion: 0.50
Nodes (3): partials.footer, partials.nav, partials.nav-script

### Community 36 - "jQuery CSS Dimension Helpers"
Cohesion: 0.67
Nodes (3): augmentWidthOrHeight(), curCSS(), getWidthOrHeight()

### Community 37 - "jQuery Promise Internals"
Cohesion: 0.67
Nodes (3): Identity(), resolve(), Thrower()

## Knowledge Gaps
- **72 isolated node(s):** `deploy-prod.sh script`, `deploy-staging.sh script`, `refresh-staging.sh script`, `Controller`, `$schema` (+67 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 286 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **11 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `Filament Panel & Auth` to `Filament Widgets & Models`, `Configuracion Model & Providers`, `Amenidad & Lead Resources`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `Propiedad` connect `Filament Widgets & Models` to `Filament Table Actions`, `Lead & Propiedad Forms`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Why does `UserResource` connect `User & Propiedad Create Pages` to `Filament Table Actions`, `Filament Resource List Pages`, `Agente Resource CRUD`, `Amenidad & Lead Resources`, `Lead & Propiedad Forms`?**
  _High betweenness centrality (0.012) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `n()` (e.g. with `c()` and `f()`) actually correct?**
  _`n()` has 2 INFERRED edges - model-reasoned connections that need verification._
- **What connects `deploy-prod.sh script`, `deploy-staging.sh script`, `refresh-staging.sh script` to the rest of the system?**
  _72 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `jQuery Vendor Bundle` be split into smaller, more focused modules?**
  _Cohesion score 0.07058823529411765 - nodes in this community are weakly interconnected._
- **Should `Composer Dependencies` be split into smaller, more focused modules?**
  _Cohesion score 0.04081632653061224 - nodes in this community are weakly interconnected._
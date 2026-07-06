# ML Sourcing — Project Context for Claude

## Stack
- Laravel 11, PHP 8.2, Blade, Bootstrap 5.3
- MySQL via WAMP (Windows dev), run via `C:\wamp64\bin\php\php8.2.29\php.exe artisan ...`
- Vite for assets (`npm run dev`)
- DataTables (server-side), Highcharts, Bootstrap 5 JS

## Architecture

### Roles
- `role = 1` → Admin
- `role = 2` → Agent (quotes, ships)
- `role = 3` → Seller (submits sourcing requests)
- `role = 4` → Comptable (approves payments)

### Core flow
1. Seller submits a sourcing request (`OrdersRequest`) via `seller.storeProductRequests`
2. System assigns the best-matching agent (by country config) → `agentID` (nullable — no agent = admin notified)
3. Agent quotes (`importedproducts.unitPrice/totalPrice` stored in **FCFA**)
4. Seller pays (screenshot upload) → `Payment` record (amount in **FCFA**)
5. Comptable approves payment
6. Agent ships → tracking number added

### Currency system
- All monetary values stored in **FCFA** in the database
- `session('currency', 'XOF')` holds the active display currency
- Helper functions in `app/Helpers/currency_helpers.php`:
  - `format_currency($fcfa)` → converts FCFA → active currency, formats string
  - `to_fcfa($amount, $code)` → converts to FCFA for storage
  - `from_fcfa($fcfa, $code)` → converts FCFA to display currency
  - `fx_rate($code)` → reads rate from `Currency` model
- **Never display raw `totalPrice` with a hardcoded `$` — always use `format_currency()`**

### Agent visibility (no longer restricted by assignment)
Agents see requests/orders matching their country config (OR scope):
```php
->where('agentID', $agentId)
->orWhere(fn($q) => $q->whereIn('countryFrom', $sourcingNames)->whereIn('countryTo', $destinationNames))
```
This applies in `filteredProductRequests`, `filteredOrders`, and `dashboard()` KPIs.

### Chat system
- `ChatThread` keyed by `order_request_id`
- `ChatMessage` with `sender_id` / `recipient_id`
- Seller's `$chatRecipientId` computed server-side in `SellerController::followUpProductRequest()`:
  last non-seller thread participant → assigned agent → admin fallback
- Agent uses `auth()->id()` as sender (not `$orderRequest->agentID`)

### Notifications
- `NotificationService::notify($user, $requestId, $key, $params, $link, ['db'])`
- Lang keys in `resources/lang/{fr,en}/notifications.php`
- When no agent matches country pair: admin is notified via `new_request_admin` key

## Key Files

| File | Purpose |
|------|---------|
| `app/Http/Controllers/SellerController.php` | Seller actions, chat recipient logic, no-agent admin notification |
| `app/Http/Controllers/AgentController.php` | Quotation, OR-scope visibility, dashboard KPIs |
| `app/Http/Controllers/AdminController.php` | Admin management, all strings via `__()` |
| `app/Http/Controllers/ComptableController.php` | Payment approval, rate updates |
| `app/Helpers/currency_helpers.php` | Currency conversion helpers |
| `app/Services/NotificationService.php` | Bilingual DB/email/SMS notifications |
| `resources/lang/fr/pages.php` | French UI strings |
| `resources/lang/en/pages.php` | English UI strings |
| `resources/lang/{fr,en}/notifications.php` | Notification content keys |
| `resources/views/auth/seller/pay.blade.php` | Seller payment page (uses `format_currency()`) |
| `resources/views/auth/agent/viewRequest.blade.php` | Agent chat (sender = `auth()->id()`) |
| `resources/views/auth/seller/viewRequest.blade.php` | Seller chat (recipient from controller) |
| `resources/views/auth/theme/dashboard.blade.php` | Base layout — mobile notification dropdown fix |
| `resources/views/auth/notifications/index.blade.php` | Notification list — mobile responsive |

## Important Decisions Made

- `agentID` is **nullable** (migration: `2026_05_14_170729_make_agentid_nullable_in_ordersrequests.php`)
- Assignment column removed from agent request table display
- Quantity minimum = 1 (not 30) in sourcing request form
- Mobile notification bell: `position: fixed` CSS in dashboard.blade.php for `max-width: 767.98px`

## Dev Setup (new machine)

```bash
# Clone
git clone https://github.com/aphenom/mlsourcing.git
cd mlsourcing
git checkout claude

# PHP dependencies
composer install

# JS dependencies
npm install

# Environment
cp .env.example .env
php artisan key:generate
# Edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Database
php artisan migrate
php artisan db:seed   # if seeders exist

# Assets
npm run dev
```

On Windows with WAMP, use the full PHP path for artisan:
```
C:\wamp64\bin\php\php8.2.29\php.exe artisan migrate
```

## Code Conventions

- All UI strings via `__('pages.key')` — never hardcoded
- Flash messages: `with('success', __('pages.key'))` or `withErrors(__('pages.key'))`
- Named parameters in translations: `__('pages.rate_updated', ['code' => $code])`
- No inline DB queries inside `@push('scripts')` — compute in controller, pass as view variable
- `generate_code()` helper for human-readable codes (CMD-, PAY-, APR-, USR-)
- Model `boot()` auto-generates codes on create

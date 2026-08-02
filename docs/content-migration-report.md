# Content Migration Report

Summary of content imported via seeders (`ContentSeeder`, `SettingsSeeder`, `NavigationSeeder`) and its publication/verification state as of initial migration.

## Published and verified

| Content type | Details |
|--------------|---------|
| **Home page** | Published with hero, metrics block, programs, featured story |
| **Programs** | 7 programs published and verified |
| **Komolion impact story** | Published, verified, safeguarding approved |
| **Webinars** | 9 webinars from 2024 series published and verified |
| **Verified metric** | “2024 webinar participants” (700+ / 792 sourced from 2024 Annual Report) |
| **Utility pages** | Accessibility, privacy, terms, cookies, FAQs, safeguarding |
| **About pages** | Who we are, mission, story, leadership placeholder, governance, partners placeholder |
| **Impact pages** | Overview, Komolion, stories index, reports, regions |
| **Get involved** | Index, membership, volunteer, partner, donate pages |
| **FAQs** | Seeded FAQ entries |
| **Form definitions** | Contact, membership, volunteer, partner, donate, newsletter |
| **Navigation** | Primary/footer menus via `NavigationSeeder` |
| **Announcement** | Active site announcement (if within date range) |

## Draft / not public

| Content type | Details |
|--------------|---------|
| **Partners** | All partner records seeded as **draft** with `needs_verification` — not shown on public partner lists until verified and published |
| **Legacy impact stats** | Workshops (150), volunteers (400), conferences (2), webinars legacy count (15) — **draft**, `needs_verification`, source “Legacy website (unverified)” |
| **Team members** | **No team profiles seeded** — leadership page explains profiles are added via CMS after verification |
| **Gallery** | Placeholder page; no albums seeded |
| **Donation campaigns** | Seeded campaign exists; payment gateway stub — financial CTAs are inquiry-based |

## Verification required

| Item | Status |
|------|--------|
| **Contact settings** | `contact.verification_status` = `needs_verification` in settings (phone/email seeded but flagged for admin confirmation) |
| **Partner logos/descriptions** | Awaiting verification before publish |
| **Legacy metrics** | Must not be published until independently verified |

## Redirects seeded

Legacy HTML paths redirect with **301**:

| From | To |
|------|-----|
| `/index.html` | `/` |
| `/service.html` | `/what-we-do` |
| `/about.html` | `/about` |
| `/contact.html` | `/contact` |
| `/gallery.html` | `/gallery` |

## Finances — not displayed

- No verified financial overview or audited figures are published on the public site.
- Impact reports page states financial overviews appear only when verified figures are available.
- Donate flow uses inquiry form; payment integration (`NullPaymentGateway`) is not live.
- “Financial Planning for Neurodivergent Children and Families” is a **webinar title**, not a published financial statement.

## Recommended next steps

1. Verify contact phone/email in **Settings → Contact** and set verification to `verified`.
2. Upload and verify partner logos; publish partners individually.
3. Add team members with consent; publish after verification.
4. Replace legacy draft metrics with sourced figures or archive them.
5. Import publications/PDFs via CMS (see `content-sources/README.md`).

## Seeder commands

```bash
php artisan migrate:fresh --seed
# Or individual seeders:
php artisan db:seed --class=ContentSeeder
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=AdminUserSeeder
```

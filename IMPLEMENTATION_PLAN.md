# Maruti Travels — Travel Agency Portal — Implementation Plan (v1)

| Field | Value |
| --- | --- |
| Agency | Maruti Travels |
| Document version | 1.0 |
| Date | 2026-05-16 |
| Status | Draft for review |
| Companion documents | PRD.md, HLD.md, LLD.md |
| Engineer model | Solo full-stack Laravel engineer |
| Cadence | Milestone-based (no fixed sprint length); demo at every milestone boundary |
| Total estimated effort | ~12 weeks of full-time work + 1 week pre-work + 1 week buffer |

---

## 1. Approach

- **Solo engineer** owns end-to-end implementation, testing, and deployment.
- **Milestone-based delivery**: each PRD §11 milestone is a self-contained, demo-able unit. Engineer ships one milestone at a time and demos it to the Maruti Travels owner before starting the next.
- **No fixed sprint length**: estimates are in working days; treat them as guides, not commitments.
- **Trunk-based development on `main` with short-lived feature branches**. Every PR runs CI; every merge to `main` deploys to production via Cloudways' Git deploy. (No staging environment in v1 — see HLD §5.2.)
- **Demo cadence**: live demo + PRD acceptance-criteria walkthrough at the end of each milestone.

## 2. Pre-Work (M0) — ~3 working days

Pre-work happens before milestone work begins. Some items can run in parallel.

### M0.1 Accounts and provisioning (engineer + owner together)
- [ ] Register the production domain in Maruti Travels' name (PRD §10 item 6 — actual domain).
- [ ] Cloudways account opened in Maruti Travels' name; payment method on file.
- [ ] Cloudflare account; DNS pointed to Cloudways IP; TLS proxied.
- [ ] Brevo account; sender domain DNS records (SPF, DKIM) verified.
- [ ] Google Cloud account; Gemini API key issued; quota confirmed.
- [ ] OpenAI account; API key issued; project budget cap set.
- [ ] GitHub organisation `maruti-travels` (or similar); private repository created; engineer added.
- [ ] UptimeRobot account; placeholder monitor created.

### M0.2 Codebase scaffold
- [ ] `laravel new portal` (Laravel 11, PHP 8.2).
- [ ] `composer require` the locked packages (see §10 below).
- [ ] `composer require --dev` PHPStan, Larastan, Laravel Pint, PHPUnit, Mockery.
- [ ] `phpstan.neon` at level 6; `pint.json` with Laravel preset; `phpunit.xml` configured.
- [ ] `.github/workflows/ci.yml` running phpunit + phpstan + pint --test on push and PR.
- [ ] `.editorconfig`, `.gitignore`, `README.md` (project overview + how-to-run).
- [ ] Repo settings: `main` is the default and protected branch; PR required; CI must pass.

### M0.3 Local dev environment
- [ ] Engineer local Laravel Herd / Sail working with MySQL 8.
- [ ] `.env.example` populated with every key from HLD §16.1.
- [ ] First green CI run.

### M0.4 Pre-work exit criteria
- [ ] Git push to `main` deploys a "Hello Maruti Travels" page to the Cloudways VPS via Cloudways Git deploy.
- [ ] HTTPS certificate active via Cloudflare.
- [ ] `/healthz` route returning 200 on production.
- [ ] CI green on `main`.

## 3. Milestones

Each milestone has: **Goal**, **Deliverables** (file/component-level), **Dependencies**, **Estimated days**, **Demo script**, **Exit criteria**.

### M1 — Foundation (4 days)

**Goal**: usable Laravel app with two-role RBAC, agency settings singleton, and audit log wiring.

**Deliverables**:
- Migrations 1–4, 16, 18 from LLD §17.
- `User` model + `staff` and `customer` guards (LLD §4.1, §12).
- Spatie roles seeded (`admin`, `agent`); `RolesAndPermissionsSeeder`.
- `AgencySetting` singleton model + seeder with placeholders.
- `LogsActivity` trait + `TracksAuthor` trait scaffolds in `app/Support/`.
- `MoneyCast`, `MoneyVo`, `BookingRefGenerator` (with `bookings_seq` table — migration 19).
- Login + email-verification + password-reset flows for staff (Breeze).
- Admin layout shell (Blade + Livewire 3 + Alpine + Tailwind), navigation skeleton with all top-level entries (greyed out until later milestones), bell icon placeholder.
- `/admin/settings` Livewire page wired but only Agency tab functional in M1.
- `/healthz` returning DB + queue lag JSON.
- `SignedFileController` skeleton (used by later milestones).

**Dependencies**: M0 complete.

**Demo script**:
1. Sign in as Admin; show settings tab; save GSTIN/state; verify activity log row written.
2. Sign in as Agent; show that Settings is hidden / 403.
3. Hit `/healthz`.

**Exit criteria**:
- All M1 PHPUnit tests pass.
- Two staff users (admin@maruti, agent@maruti) seeded for demo.

---

### M2 — Customers (2 days)

**Goal**: customer master CRUD with GSTIN validation.

**Deliverables**:
- Migration 3 already from M1; adjust if needed.
- `Customer` model + relationships (LLD §4.2).
- `CustomerService` (create, update, soft-delete).
- `CustomersIndex` + `CustomerForm` Livewire components (LLD §5.1).
- `StoreCustomerRequest`, `UpdateCustomerRequest` (LLD §6.1).
- `CustomerPolicy`.
- Search by name/phone/email/GSTIN.
- Customer detail page with tab placeholders for Enquiries/Trips/Bookings/Documents (filled by later milestones).

**Dependencies**: M1.

**Demo script**:
1. Add a customer with full GSTIN; show validation rejecting a malformed GSTIN.
2. Search by phone.
3. Soft-delete a customer; verify hidden from list but row preserved.

**Exit criteria**: T1 (signup login) deferred to M5; M2-specific tests pass.

---

### M3 — Vendors (1 day)

**Goal**: vendor master, Admin-only.

**Deliverables**:
- `Vendor` model + migration 5.
- `VendorService`.
- `VendorsIndex` + `VendorForm` Livewire (Admin-only via middleware + policy).
- Soft-delete enabled.

**Dependencies**: M1.

**Demo**: as Admin add a vendor; switch to Agent and confirm vendor master is hidden from nav and 403 on direct URL.

---

### M4 — Standard Packages (4 days)

**Goal**: package master + customer-facing browse pages.

**Deliverables**:
- Migration 6 (`packages`, `package_images`, `itinerary_days`).
- `Package`, `PackageImage`, `ItineraryDay` models (LLD §3.10, §4.4).
- `PackageService` + `PackagesIndex`/`PackageForm` Livewire.
- Rich-text editor for long description (TinyMCE or Trix; pick TinyMCE for simplicity).
- Image gallery upload with image validation; resized variants generated by `intervention/image`.
- Day-wise itinerary repeater UI.
- Public pages: `/packages` (filterable list) and `/packages/{slug}` (detail with gallery, itinerary, "Send enquiry" CTA).
- Static `/`, `/about`, `/contact` pages with Maruti Travels placeholder copy.

**Dependencies**: M1.

**Demo**: create a "Goa 4N5D" demo package with 3 itinerary days and 5 gallery images; view it on the public site; verify SEO meta tags render.

**Exit criteria**: customer site accessible without login; package list paginates; package detail loads sub-1.5s.

---

### M5 — Customer Portal Shell (3 days)

**Goal**: customer signup, login, profile, dashboard skeleton.

**Deliverables**:
- Customer guard wired (LLD §12 `config/auth.php`).
- Signup, login, password-reset routes (LLD §5.2).
- Email verification mandatory on customer side too.
- `CustomerDashboard` and `CustomerProfile` Livewire components.
- "Send enquiry from package" CTA pre-fills enquiry form (form built in M6).
- Customer-area middleware enforcing `auth:customer` + `verified`.

**Dependencies**: M2 (customer master), M4 (so signup links to public site).

**Demo**: visitor signs up, verifies email, logs in, sees empty dashboard, edits profile.

**Exit criteria**: T1 acceptance test (signup + verify + login) passes.

---

### M6 — Enquiries (4 days)

**Goal**: enquiry inbox + customer-side form + standard reply templates.

**Deliverables**:
- Migration 7 (enquiries, notes, attachments, reply templates).
- `Enquiry`, `EnquiryNote`, `EnquiryAttachment`, `EnquiryReplyTemplate` models.
- `EnquiryService`, `EnquiryReplyService`.
- `EnquiriesIndex`, `EnquiryForm` Livewire (admin); `CustomerEnquiriesIndex` + `StoreCustomerEnquiry` (customer).
- `StoreEnquiryRequest`, `StoreCustomerEnquiryRequest` (LLD §6.2, §6.3).
- `EnquiryReceived` notification (mail to customer on submission).
- `EmailTemplateReplyJob` (queued; sends template-based reply, logs in timeline).
- Reply templates seeder with: enquiry confirmation, generic clarification, follow-up, lost-lead.
- Status pipeline + bulk-assign UI.

**Dependencies**: M2, M5.

**Demo**:
1. Customer raises enquiry from a package page; sees confirmation email.
2. Admin sees it in inbox; assigns to Agent; sends "ask for travel dates" template reply; verifies email logged in timeline.
3. Agent transitions status `New → In Progress → Quoted`.

**Exit criteria**: T-references PRD §9 #3 partial (signup + enquiry creation).

---

### M7 — Trips and Quotations (6 days)

**Goal**: trip container + multi-line versioned quotations with GST.

**Deliverables**:
- Migrations 8, 9.
- `Trip`, `Quotation`, `QuotationVersion`, `QuotationLine` models.
- `TripService`, `QuotationService` (LLD §8.2).
- `GstCalculator` service (LLD §8.6) with full unit tests covering same-state and cross-state customers.
- `TripsIndex`, `TripForm`, `QuotationList`, `QuotationEditor` Livewire components.
- `StoreQuotationVersionRequest` with the financial-field stripping pattern (LLD §6.4).
- Versioning logic: editing a sent quotation creates a new version; previous versions remain accessible.
- Conversion path from accepted enquiry → trip → quotation v1.
- `LogsActivity` trait active on Quotation, QuotationVersion, QuotationLine.
- History tab on quotation detail (Admin only).

**Dependencies**: M3 (vendor lookup on lines), M6 (enquiry conversion path).

**Demo**:
1. Convert an existing enquiry to a trip + quotation v1.
2. Add flight + hotel + package lines with discount; verify GST CGST+SGST split for same-state customer; verify IGST for cross-state.
3. Edit and save → v2 created; v1 still visible in history.
4. As Agent, verify cost/margin columns hidden; as Admin, verify History tab visible.

**Exit criteria**: T2, T3, T12, T13 acceptance tests pass.

---

### M8 — Documents Engine (4 days)

**Goal**: PDF generation for quotations and GST invoices, with email send.

**Deliverables**:
- Migration 11 (documents).
- `Document` model.
- `PdfRenderer` interface + `SnappyPdfRenderer` + `DompdfRenderer` + `PdfRendererFactory` (LLD §14).
- `QuotationPdfService`, `InvoiceService`.
- Blade PDF templates (LLD §14): `quotation.blade.php`, `gst_invoice.blade.php` with placeholders for branding and document fields.
- `GenerateQuotationPdfJob`, `EmailQuotationJob` (LLD §10.1).
- `QuotationSent` notification class with PDF attachment.
- Configurable header/footer/logo/colour via `agency_settings` (Settings → Branding tab).
- Document templates editor scaffold (advanced fields; only branding inputs functional in M8).
- Versioned storage path layout (LLD §15).

**Dependencies**: M7.

**BLOCKED ON OWNER INPUT**: PRD §10 item 6 (branding). Engineer can use placeholder logo and Lorem T&Cs to ship the milestone; Maruti Travels' real assets must be substituted before any customer email goes out in production.

**Demo**:
1. Generate quotation PDF v1; download and email; show received email with PDF attached.
2. Switch logo in Settings → re-generate; show updated branding.
3. Disable wkhtmltopdf temporarily on the VPS → factory falls back to dompdf; PDF still generated.

**Exit criteria**: T14 acceptance test passes.

---

### M9 — Bookings and Passengers (5 days)

**Goal**: per-service bookings with multi-pax, agency PNR, financials.

**Deliverables**:
- Migration 10 (passengers, bookings, booking_passenger).
- `Booking`, `Passenger` models (LLD §4.3, §4.4).
- `BookingService` (LLD §8.1).
- `BookingsIndex`, `BookingForm` Livewire (per-type sub-form for flight/hotel/package).
- `StoreBookingRequest` with field-stripping (LLD §6.5).
- "Copy passengers from another booking" helper inside trip detail.
- Booking ref generator wired (M1's `bookings_seq` table now used).
- Activity log on Booking + Passenger.
- History tab on booking detail (Admin only).
- Booking detail tabs: Details, Passengers, Documents, Supplier docs (placeholder until M11), Change requests (placeholder until M12), History (Admin only).

**Dependencies**: M3, M7.

**Demo**:
1. From an accepted quotation, create three bookings (flight, hotel, package); enter agency PNRs.
2. Add 4 passengers (2 adult, 2 child) to flight booking; mark one as lead.
3. Use "copy passengers" to populate hotel booking from the same set.
4. Admin views History tab → all field-level changes logged. Agent sees no History tab and 403 on direct URL.

**Exit criteria**: T4, T5, T7 (partial), T12 acceptance tests pass.

---

### M10 — Vouchers (3 days)

**Goal**: branded voucher PDFs for flight/hotel/package, with drop-and-create.

**Deliverables**:
- Blade PDF templates: `flight_voucher.blade.php`, `hotel_voucher.blade.php`, `package_voucher.blade.php`.
- `VoucherService`, `VoucherPdfService` (LLD §8.3).
- `GenerateVoucherJob`, `EmailVoucherJob`.
- `VoucherIssued` notification class.
- "Generate voucher" button on each booking; "Email to customer" button; "Download" button.
- Drop-and-create logic: regenerating creates a new `documents` row with incremented `version_number`; previous versions retained.
- Documents tab on booking detail lists all generated vouchers with download links.

**Dependencies**: M8, M9.

**Demo**:
1. Generate flight voucher; email to customer; download.
2. Edit booking departure time; regenerate voucher → v2 created; v1 still downloadable.
3. Verify customer receives the voucher email with the PDF attached.

**Exit criteria**: PRD acceptance criterion #1 (end-to-end happy path) demo-able.

---

### M11 — Supplier Documents and AI Extraction (7 days)

**Goal**: supplier-doc upload (manual + AI-assisted), with two-provider fallback and budget tracking.

**Deliverables**:
- Migration 12 (supplier_documents, extraction_jobs).
- `SupplierDocument`, `ExtractionJob` models.
- `SupplierDocService` (LLD §8.4).
- `AiVisionClient` interface, `GeminiClient`, `OpenAiClient`, `NullAiClient` (LLD §13).
- JSON schemas: `FLIGHT_SCHEMA`, `HOTEL_SCHEMA` (LLD §13.3).
- `ExtractAction` queueable job with primary→fallback logic (LLD §8.10).
- `AiBudgetTracker` service (LLD §8.9) + monthly cap UI in Settings → AI tab.
- Admin override flow when budget cap is breached (PRD §5.12 cost note).
- `SupplierDocUploadRequest` (LLD §6.6).
- Standalone uploader page (`/admin/supplier-docs/new`) — multi-step Livewire wizard.
- "Upload supplier document" button on existing booking detail; "Supplier documents" tab.
- Confidence-based field highlighting on AI-prefilled booking form (CSS class `lw-low-confidence`, threshold from `agency_settings`).
- "Supplier-doc extraction log" report (Admin only).
- Activity log on SupplierDocument.

**Dependencies**: M9, M10.

**BLOCKED ON OWNER INPUT**: PRD §10 item 15 (top supplier portals) — needed to gather sample PDFs (1–2 per supplier) before testing AI extraction accuracy.

**Demo**:
1. Manual mode: upload a Tripjack flight ticket; key fields manually; save; verify Maruti voucher generates correctly.
2. AI mode: upload a hotel voucher; watch extraction job progress; verify fields prefilled with low-confidence highlights; review and save; verify activity log shows what was edited.
3. Force Gemini to fail (toggle env to bad key); verify automatic fallback to OpenAI.
4. Set monthly cap to ₹10 and trigger many extractions; verify Agent gets blocked and Admin sees override button (logged).

**Exit criteria**: T7, T8, T9 acceptance tests pass.

---

### M12 — Cancellation and Change Requests (3 days)

**Goal**: cancellation/change workflow with refund tracking and customer-facing summary.

**Deliverables**:
- Migration 13 (change_requests, change_request_notes).
- `ChangeRequest` model.
- `ChangeRequestService` (LLD §8.5) + `RefundCalculator` (the `net_refund_to_customer` is a generated column but UI also previews it).
- `ChangeRequestsIndex`, `ChangeRequestForm` Livewire.
- `StoreChangeRequestRequest` (LLD §6.7).
- `BookingCancelledSummary` notification.
- On completion: parent booking status sync (Cancelled / updated for change); voucher regenerated for change requests (drop-and-create reuse).
- Customer-side route: `POST /account/bookings/{ulid}/cancellation` to allow customers to raise cancellation requests directly.
- Activity log on ChangeRequest.

**Dependencies**: M9, M10.

**Demo**:
1. Customer raises cancellation from "My Trips"; admin sees it in inbox.
2. Admin enters vendor fee ₹2000, refund from vendor ₹8000, agency service fee ₹500; verify net refund ₹5500 (computed by generated column) shown in real time.
3. Mark Completed → booking status `Cancelled`; customer receives summary email.
4. History tab shows the field-level diff (Admin only).

**Exit criteria**: T11 acceptance test passes.

---

### M13 — Reminders Engine (3 days)

**Goal**: scheduled reminders for travel events and payment dues, in-app + email.

**Deliverables**:
- Migration 14 (reminders).
- `Reminder` model.
- `ScanRemindersCommand` (LLD §8.8) and `SendReminderJob`.
- 5 notification classes (PRD §5.9 / LLD §10.2).
- Reminder lead-time settings UI (Settings → Reminders tab).
- Bell icon component wired to `RemindersInbox` Livewire (LLD §9.5).
- Dedup-key strategy verified with concurrency test.
- Schedule entry: `reminders:scan` daily at 06:00 IST.

**Dependencies**: M9, M12.

**Demo** (uses Carbon time-freezing in a sandbox setting):
1. Set system time to T-49h before a flight booking; run `php artisan reminders:scan`; verify one `web_checkin` reminder created and email + bell notification sent to assigned agent.
2. Set time to T-2h before; run again; verify no duplicate reminder created.
3. Trigger customer-payment-due reminder at T-7 days from a real customer due date.

**Exit criteria**: T10, T15 acceptance tests pass.

---

### M14 — Dashboard and Reports (4 days)

**Goal**: dashboards for Admin and Agent, plus exportable reports.

**Deliverables**:
- `DashboardController` with role-aware widgets.
- Admin charts: monthly bookings trend, monthly revenue, service-type mix, top 10 customers, top 10 vendors. Use Chart.js loaded from CDN.
- Agent dashboard: counters + "my upcoming follow-ups" panel; no revenue charts.
- `ReportQueryService` consolidating cross-module queries.
- `ReportsExportController` with `maatwebsite/excel` exports for: bookings register, sales-profit (Admin only), enquiry conversion (overall + per-source where populated), cancellations, payments-customer, payments-vendor (Admin only).
- "Failed background jobs" widget (Admin only).
- "AI extraction log" report from M11 hooked into the Reports menu.

**Dependencies**: M9, M11, M12.

**Demo**:
1. Show Admin dashboard with seeded demo data; verify charts render.
2. Switch to Agent; verify revenue/profit charts hidden.
3. Export bookings register to Excel; open and verify columns.
4. Export sales-profit as Admin (works); attempt as Agent → 403.

**Exit criteria**: T5 acceptance criterion (Agent 403 on sales-profit) passes.

---

### M15 — Customer Portal Full (3 days)

**Goal**: customer self-serve trip detail with vouchers and cancellation requests.

**Deliverables**:
- `CustomerTripsIndex`, `CustomerTripDetail` Livewire components.
- Booking-level voucher download via signed URL (`/account/files/{token}`).
- "Request cancellation" button on each booking; opens minimal form that submits a `change_request` row (admin processes the rest in M12 backend).
- Strict policy tests: cross-customer access denied; financial fields stripped from response payloads.

**Dependencies**: M5, M9, M10, M12.

**Demo**:
1. Customer logs in, sees the demo trip with three bookings and three vouchers; downloads each.
2. Customer raises cancellation from "My Trips"; admin sees it.
3. Engineer attempts URL tampering as customer A to access customer B's trip → 403.

**Exit criteria**: T6 acceptance test passes; PRD acceptance criterion #3 fully covered.

---

### M16 — Hardening and UAT (5 days)

**Goal**: production-ready, security-reviewed, owner-acceptance-tested.

**Deliverables**:
- Internal security review pass (rate limiting, file-upload validation across all upload sites, signed-route coverage, RBAC matrix verified end-to-end).
- Cloudways snapshot retention configured (≥ 7 daily snapshots).
- UptimeRobot monitor pointed at `/healthz`; alert email to engineer + owner.
- Brevo bounce/complaint webhook documented (manual review only in v1).
- All 15 must-have tests in LLD §16.2 green.
- UAT script executed with Maruti Travels owner — every PRD §9 acceptance criterion ticked off.
- README expanded with operational runbook (HLD §13).
- `.env.production` finalised (real domain, real API keys, real Brevo creds).
- DNS TTLs reduced 24h before go-live for fast rollback.
- Real Maruti Travels data substituted: branding, default T&Cs, GSTIN, GST rates, agency address.
- Owner sign-off email captured.

**Dependencies**: All previous milestones.

**BLOCKED ON OWNER INPUT**: PRD §10 items 2 (GSTIN), 3 (GST rates with CA), 6 (branding) — must be supplied no later than start of M16. Item 15 (supplier portals) was used in M11 already.

**Demo**: full PRD §9 acceptance walkthrough with the owner driving.

**Exit criteria**: production cutover, owner sign-off, monitoring green for 72 hours.

## 4. Effort Summary

| Phase | Days |
| --- | --- |
| M0 Pre-work | 3 |
| M1 Foundation | 4 |
| M2 Customers | 2 |
| M3 Vendors | 1 |
| M4 Standard Packages | 4 |
| M5 Customer Portal Shell | 3 |
| M6 Enquiries | 4 |
| M7 Trips and Quotations | 6 |
| M8 Documents Engine | 4 |
| M9 Bookings and Passengers | 5 |
| M10 Vouchers | 3 |
| M11 Supplier Documents and AI | 7 |
| M12 Cancellation and Change | 3 |
| M13 Reminders | 3 |
| M14 Dashboard and Reports | 4 |
| M15 Customer Portal Full | 3 |
| M16 Hardening and UAT | 5 |
| **Subtotal** | **64** |
| Buffer (15%) | 10 |
| **Total** | **74 working days** |

At 5 working days/week → ~15 calendar weeks. Owner-facing communication target: **v1 in production within 4 months of M0 kickoff**, accommodating typical small-team interruptions and review cycles.

## 5. Critical Path and Owner-Input Dependencies

The end-to-end critical path (longest dependency chain) is:

```
M0 -> M1 -> M2 -> M5 -> M6 -> M7 -> M8 -> M9 -> M10 -> M11 -> M12 -> M14 -> M15 -> M16
```

Off-critical-path milestones (M3 Vendors, M4 Packages, M13 Reminders) can run earlier or in parallel if the engineer ever needs a "low-risk" task while waiting for owner input.

### Owner-input deadlines (working backwards from milestones)

| Item | Needed by | Risk if late |
| --- | --- | --- |
| Production domain (M0.1) | Start of M0 | Pre-work cannot complete; defer to M16, demo on a placeholder domain. |
| Cloudways / Brevo / Google / OpenAI / GitHub accounts | Start of M0 | Same as above. |
| Branding assets (logo, address, T&Cs, accent colour) — PRD §10 #6 | Start of M8 | Engineer ships with placeholders; replace before any real customer email goes out. |
| Supplier portal list and 2–3 sample PDFs each — PRD §10 #15 | Start of M11 | AI extraction cannot be tuned/tested. M11 ships with synthetic samples; real-world accuracy unknown until owner provides. |
| GSTIN + registered state — PRD §10 #2 | Start of M16 | Cannot generate real GST invoices. Demo only. |
| GST rates confirmed by CA — PRD §10 #3 | Start of M16 | Same as above. Engineer uses defaults (5% / 18%) until then. |

If any owner-input item slips, the engineer continues on adjacent milestones; only M16 (production cutover) hard-blocks on items 2, 3, 6.

## 6. Tooling and Locked Package Versions

These are the dependencies the engineer pulls in M0.2. Versions are minimums; use the latest minor at install time.

```
composer require:
  laravel/framework            ^11.0
  laravel/breeze               ^2.0
  livewire/livewire            ^3.0
  spatie/laravel-permission    ^6.0
  spatie/laravel-activitylog   ^4.7
  barryvdh/laravel-snappy      ^1.0
  barryvdh/laravel-dompdf      ^3.0
  maatwebsite/excel            ^3.1
  intervention/image           ^3.0
  mews/purifier                ^3.4
  league/csv                   ^9.0
  gemini-api-php/laravel       ^1.0
  openai-php/laravel           ^0.10
  symfony/process              ^7.0    # used by snappy availability probe

composer require --dev:
  larastan/larastan            ^2.9
  phpunit/phpunit              ^11.0
  laravel/pint                 ^1.13
  mockery/mockery              ^1.6
  laravel/dusk                 ^8.0    # for the small Dusk smoke suite

System packages on the VPS:
  wkhtmltopdf 0.12.6
  mysql-server 8.x
  php8.2-fpm + php8.2-{mbstring,xml,bcmath,gd,zip,curl}
  nginx
  supervisor
```

## 7. Risk Register (execution-specific)

This is in addition to the design risks already captured in HLD §14.

| # | Risk | Likelihood | Impact | Mitigation |
| --- | --- | --- | --- | --- |
| E1 | Owner input #6 (branding) arrives during M16 instead of M8 | High | Medium | Engineer ships M8–M15 with placeholders; M16 includes a one-day buffer for branding swap-in. |
| E2 | Real supplier-doc samples for AI tuning don't arrive until late | High | High | Engineer builds M11 against synthetic samples; commits a "test extraction" tool for the owner to validate against real docs after M11 ships. |
| E3 | wkhtmltopdf is not installable on the chosen Cloudways stack | Low | Medium | Factory falls back to dompdf automatically (LLD §14). Engineer probes this on M0 day 1, not at M8. |
| E4 | Brevo sender domain DKIM not approved in time | Medium | High | Initiate DKIM verification on M0; mailbox ready by M6. If delayed, fall back to a generic Brevo sender for development; do not go live without DKIM. |
| E5 | Engineer attrition mid-build | Low | Critical | Document-first culture (PRD/HLD/LLD already exist) reduces ramp-up; trunk-based development keeps work visible; weekly demo cadence preserves owner context. |
| E6 | Cloudways account compromise | Low | Critical | Provider 2FA enabled on day 1 (HLD §14); recovery info documented; treat Cloudways credentials as the most sensitive asset. |
| E7 | Single-VPS outage during a demo | Medium | Low | Restore from latest snapshot; communicated downtime ≤ 4 hours per HLD §9.10 RTO. |
| E8 | Underestimated complexity in Quotation versioning (M7) | Medium | Medium | M7 has the largest day-allocation already (6 days); buffer in §4 absorbs further slip. |

## 8. Definition of Done — per milestone

For a milestone to count as "done":

1. All deliverables in the milestone's section above are merged to `main`.
2. CI is green on `main`.
3. Migrations applied to production via Cloudways Git deploy.
4. Acceptance criteria in PRD §9 mapped to that milestone are demoed live to the owner.
5. Activity log shows expected diffs where applicable.
6. No new failed-jobs entries for 24 hours after the demo.
7. `README.md` updated if any operational change was introduced.

## 9. Go-Live Checklist (executed during M16)

- [ ] Real domain DNS pointed at Cloudways via Cloudflare; HTTPS active.
- [ ] `APP_DEBUG=false`, `APP_ENV=production` in Cloudways env.
- [ ] All `.env` secrets set (DB, Brevo, Gemini, OpenAI).
- [ ] `php artisan optimize` and `php artisan event:cache`, `route:cache`, `view:cache` run via Cloudways post-deploy hook.
- [ ] Cloudways snapshot retention ≥ 7 daily; manual snapshot taken just before cutover.
- [ ] UptimeRobot monitor green for 30 minutes.
- [ ] Real Maruti Travels GSTIN, state, GST rates loaded into `agency_settings`.
- [ ] Real branding assets uploaded (logo, address, T&Cs, accent colour).
- [ ] Two staff users created with real emails; demo seed data removed from prod.
- [ ] Email deliverability verified end-to-end with the owner's real mailbox.
- [ ] One real flight ticket and one real hotel voucher run through the AI extraction flow successfully.
- [ ] Backup runbook printed/saved offline; snapshot rollback drill rehearsed at least once.
- [ ] Owner has Cloudways admin login (read-only or full per their preference) and Brevo dashboard access.
- [ ] Owner sign-off email captured.

## 10. First 30 Days After Go-Live

- Daily: engineer checks `failed_jobs`, snapshot-taken status, UptimeRobot history.
- Weekly: review of AI extraction success rate (target ≥ 85%), monthly cost-so-far vs cap, top failing jobs.
- Day 14: review with owner — what's working, what's friction, top 3 small-fixes.
- Day 30: retrospective; backlog grooming for phase 2.

## 11. Phase 2 Backlog (deferred from v1)

Pulled forward from PRD §12, in rough priority order:

1. **Off-server backup** — re-enable `spatie/laravel-backup` to Wasabi/B2; the integration scaffold is already in the repo (just disabled).
2. **Online payments** — Razorpay payment links on quotations and bookings.
3. **WhatsApp + SMS** — for vouchers, payment reminders, OTP for customer login.
4. **Refund / credit-note PDF auto-generation** — reuses M8 PDF engine.
5. **Template-based supplier-doc parsers** — for the top 3–5 portals Maruti Travels uses; cheaper than per-document AI calls.
6. **Sentry / external error tracking**.
7. **Staging environment**.
8. **Field-level audit log retention policy** — pruning job.
9. **Admin 2FA** (TOTP).
10. **Anti-malware** (ClamAV) on uploads.
11. **Confidence-threshold auto-accept** for high-confidence extractions.
12. **Multi-branch + ledger accounting** (phase 4 territory but tracked here).

## 12. Document Cross-Reference

| Concern | PRD § | HLD § | LLD § | Plan § |
| --- | --- | --- | --- | --- |
| Scope and milestones | 11 | 6 | 17 | 3 |
| Tech stack and packages | 8 | 2.3, 8 | 2, 12 | 6 |
| Acceptance criteria | 9 | — | 16.2 | 3 (per milestone), 9 |
| RBAC matrix | 3 | 9.2 | 7.1 | (verified in M1, M9, M14) |
| Owner-input items | 10 | 15 | — | 5 |
| Risks | — | 14 | — | 7 (execution add-on) |
| Backups / DR | 7.3 | 9.10 | — | 9 (cutover) |

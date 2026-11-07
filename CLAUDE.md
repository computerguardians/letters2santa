# Letters2Santa — Build Brief for Claude Code

Put this file in the root of the letters2santa repo as `CLAUDE.md`.
Owner: Anthony Sequeira (Computer Guardians). Solo developer, so keep the code simple and conventional.
Hard deadline: **Santa emails go out on 25 Dec 2026.** Business party packs must be working by **late November**, when party season starts.

---

## 0. Before writing any code

1. Look through the existing letters2santa codebase and report back:
   - stack, framework version, hosting assumptions, database, existing routes, pages, and anything reusable
   - what is missing compared with this brief
2. Suggest a plan that **extends** the existing site rather than rewriting it, unless it's static HTML or not worth keeping. In that case, propose Laravel and wait for approval.
3. Wait for approval before starting Phase 1.

**Preferred stack if building fresh:** Laravel (latest stable), MariaDB, Redis queues, Blade + Alpine/Livewire, Filament for admin, Stripe Checkout + webhooks, AWS SES for email, S3 for uploads, AWS Rekognition for image moderation. Deploy to an Ubuntu VPS (nginx, PHP-FPM, Supervisor for queue workers, cron for `schedule:run`).

---

## 1. Product summary

Kids write a letter to Santa (text and/or a photo of their drawing or handwritten letter). A parent registers and pays **A$1 per letter**. The family gets:
- an **Elf email** straight away: "Your letter has arrived at the North Pole!"
- a **Santa email on 25 Dec**, personalised with the child's first name and town

**Party hosts (businesses, schools, councils, venues)** prepay letter credits at **A$0.80 per letter**, choosing the amount with a slider. They get a QR code poster for their event. Guests scan it and send letters **free**, using the host's credits.

A public **Live Letter Wall** shows a big worldwide counter plus a feed like "Emma from Tarneit, Australia 🇦🇺".

Admins can pick **Top Letters of the Day** and export them as Instagram images (only with parental consent).

---

## 2. Roles

| Role | Can do |
|---|---|
| Parent (account holder, must be 18+) | Register, write letters for one or more children, pay, see sent letters |
| Business host | Register business, buy credit packs, create events, download QR poster, view dashboard |
| Party guest (no account) | Scan QR, fill short form with parent email, send letter on host's credits |
| Admin | Moderation queue, refunds, featured letters, wall settings, email preview/test |

The account holder is always the adult. **Children never create accounts.**

---

## 3. Core flows

### A. Individual letter (A$1)
1. Register or log in with email verification. Store timezone (detect from browser, editable).
2. Write letter form:
   - child first name (required, max 30 chars, **first name only**)
   - age (optional, select 2–12)
   - town/suburb + country (required; country from a select list)
   - letter text (optional, max 2,000 chars)
   - image upload (optional, up to 2 images; jpg/png/heic/webp; max 10 MB each; convert HEIC to JPG; strip EXIF/GPS data; make a 1080px version and a thumbnail)
   - must have text or at least one image
   - consent checkboxes, **both unticked by default**:
     - "Show first name and town on the public Letter Wall"
     - "Santa's elves may feature this letter on Instagram (first name and town only)"
3. Stripe Checkout for A$1 (AUD). Allow several letters in one checkout (siblings) to save on per-transaction fees.
4. Stripe webhook `checkout.session.completed` marks the letter `paid`. **Never trust the redirect alone.**
5. Queue the Elf email straight away.
6. Schedule the Santa email for **25 Dec, 7:00 am in the account's timezone**.
7. Cutoff: letters paid after the configurable `SANTA_CUTOFF` (default 24 Dec 6 pm AEDT) get the Santa email within 1 hour of payment instead. Show that on the form after the cutoff.

### B. Business party pack (A$0.80/letter)
1. Business registration: business name, ABN (optional), contact name, email, phone, country.
2. "Create Event": event name, date, location (town/country), optional logo upload.
3. Credit slider: **min 20, max 2,000, step 10**, live price shown (`credits × 0.80`). Stripe Checkout. GST-inclusive pricing, tax invoice emailed (Stripe invoice or PDF).
4. On payment: event gets `credits_remaining`. Generate:
   - a unique public URL `/party/{slug}` (random slug, not guessable from the business name)
   - a QR code (SVG + PNG)
   - a **printable A4 poster PDF**: "Write to Santa! Scan to send your letter to the North Pole", QR, event name, optional host logo
5. Guest flow at `/party/{slug}`: mobile-first single page. Fields as in flow A, **plus parent email (required)** and a tickbox "I am the parent/guardian". No account or payment needed. On submit, deduct 1 credit inside a DB transaction with row locking, so credits can't go negative under concurrent submissions.
6. When credits reach 0: show "This party's letters have all been sent! You can still send yours for $1" and fall back to flow A checkout as a guest.
7. Host dashboard:
   - letters received (count + list: first name, town, time, thumbnail)
   - credits remaining, top-up button (same slider)
   - download QR / poster
   - pause/close event
   - live counter for that event (to show on a TV at the party: `/party/{slug}/screen`)
   - CSV export (first name, town, time; **no parent emails** — hosts don't get guest contact details)

### C. Live Letter Wall (`/wall`, also on the homepage)
- Big animated counter: "**12,483 letters have reached the North Pole**"
- Live feed of recent letters: first name, town, country flag, "2 minutes ago"
- Only letters that are **paid/credited + approved + have wall consent** appear in the feed. All paid letters count toward the number.
- Update by polling every 10 seconds (cached JSON endpoint, 5 s cache). Laravel Reverb is optional later.
- Optional later: world map with dots per country.
- **Seed/sample data:** add a seeder with ~50 sample entries flagged `is_sample = true`. Config `WALL_SHOW_SAMPLES` (default true until 500 real letters). Sample entries:
  - appear in the feed only, labelled subtly (e.g. "✨ sample"), and are **never added to the counter number**
  - are automatically hidden once real letters reach the threshold

### D. Moderation
- Every letter starts `pending`.
- Auto-checks: Rekognition `DetectModerationLabels` on images; profanity/PII filter on text and child name (block phone numbers, emails, street addresses, surnames when detectable).
- Clean letters are auto-approved. Flagged letters go to the admin queue (Filament) with approve / reject / edit-name options.
- Rejected letters still get Elf and Santa emails. They just never appear publicly.

### E. Top Letters of the Day → Instagram
- Admin "Feature" button, available **only** when `instagram_consent = true`.
- Generates a 1080×1080 and a 1080×1350 image card: letter image or text excerpt, "— Emma, 7, Tarneit 🇦🇺", Letters2Santa branding. Use Browsershot or Intervention Image.
- Public `/top-letters` page shows featured letters.
- Posting to Instagram is manual for now (download button). Meta API automation is out of scope.

### F. Emails (AWS SES, queued)
- **Elf email**: festive HTML template, "Hi {parent}, Elf Jingles here! {child}'s letter from {town} arrived safely at the North Pole at {time}. Santa reads every letter…", with letter thumbnail.
- **Santa email**: warm, personalised with child name, town, and age if given. Write **3–4 template variants**, chosen randomly so siblings don't get identical letters. Include a printable PDF "Letter from Santa" attachment or download link.
- Optional phase 3: AI-personalised Santa reply that references the letter content. Keep it behind a feature flag and moderate the output.
- Admin can preview and send test versions of each email.
- Use SPF/DKIM/DMARC on the sending domain. Throttle the 25 Dec batch through the queue to stay within SES rate limits, and test with a 1,000-email dry run before December.

---

## 4. Data model (starting point)

- `users`: id, name, email, password, timezone, role (parent|business|admin), email_verified_at
- `businesses`: id, user_id, name, abn, phone, country, logo_path
- `events`: id, business_id, name, slug, event_date, town, country, credits_purchased, credits_remaining, status (active|paused|closed)
- `credit_purchases`: id, event_id, credits, amount_cents, stripe_session_id, status
- `letters`: id, user_id (nullable), event_id (nullable), parent_email, child_first_name, child_age, town, country_code, body, source (individual|party), payment_status, moderation_status, wall_consent, instagram_consent, is_sample, featured_at, elf_sent_at, santa_scheduled_for, santa_sent_at, created_at
- `letter_images`: id, letter_id, path, thumb_path, moderation_labels (json)
- `payments`: id, user_id, stripe_session_id, amount_cents, status, letter_ids (json or pivot)

Use database indexes on `payment_status`, `moderation_status`, `santa_scheduled_for`, and `events.slug`.

---

## 5. Privacy, safety and compliance (non-negotiable)

This service collects children's names, locations and artwork, so:
- Adult account holder or guardian confirmation on every letter. No child accounts.
- **First name + town/country only** in public places. Never surname, street, school, or face photos made public. If Rekognition detects a face in an uploaded image, keep it out of public display and Instagram.
- Strip EXIF/GPS metadata from all uploads.
- Store uploads in a **private** S3 bucket; serve with signed URLs. Public wall thumbnails only for approved + consented letters.
- Consent stored with a timestamp. Parents can withdraw consent or delete a letter from their account.
- Privacy policy and terms pages written for the Australian Privacy Act. Add a data retention job: delete images and letter text **90 days after 25 Dec** (configurable); keep only anonymised counts.
- Rate limiting on party pages (per IP + per event) and reCAPTCHA/Turnstile to prevent credit draining.
- Stripe webhooks verified with signing secret; payment logic idempotent.

---

## 6. Build phases

**Phase 1 (target: ~21 Nov)**
- Step 0 review, auth, individual letter flow, uploads, Stripe, Elf email, Santa email scheduling, basic admin moderation

**Phase 2 (target: ~28 Nov)**
- Business registration, credit slider, events, QR + poster PDF, guest party flow, host dashboard, party screen view

**Phase 3 (early Dec)**
- Live Letter Wall polish, sample-data rules, Top Letters + Instagram card export, `/top-letters`

**Before 20 Dec**
- Full Santa-email dry run on staging (with time travel via `Carbon::setTestNow` / a test command)
- Load test party page (100 concurrent submits on one event)
- Backup and monitoring check

---

## 7. Working rules for Claude Code

- Use plan mode for each phase; show the plan before coding.
- Write Pest/PHPUnit feature tests for: payment webhook, credit deduction under concurrency, Santa scheduling across timezones, consent filtering on the wall, cutoff behaviour.
- Keep all prices, limits and dates in `config/letters.php` / `.env`. Don't hard-code them.
- Use Stripe test mode keys only until told otherwise.
- Don't commit secrets. Keep `.env.example` updated.
- At the end of each phase: list what was built, how to test it manually, and any open decisions.

=== Audira ===
Premium block theme (Full Site Editing) for a U.S. Amazon Associates site about hearing aids for older adults.
Requires WordPress 6.5+ and PHP 7.4+.

== Install ==
1. Appearance → Themes → Add New → Upload Theme → choose audira.zip → Install → Activate.
2. Settings → Audira Affiliate: paste your Amazon Associate tag (e.g. yourstore-20) and save.
3. Edit inc/catalog.php (Appearance → Theme File Editor, or via FTP/File Manager) and replace each
   B0XXXXXXXX placeholder with the real ASIN. Update titles and bullets to match each product.
4. Settings → Permalinks → "Post name" → Save.
5. Settings → Privacy → create/publish your Privacy Policy page.

On activation the theme creates and publishes: Home (set as front page), About,
Affiliate Disclosure and Medical Disclaimer.

== How affiliate links work ==
Every Amazon link rendered on the site — theme buttons, "add to cart" quantity buttons and links you
type in the editor — is rewritten with the tag saved in Settings → Audira Affiliate.

== Structure ==
- inc/catalog.php: products, ASINs, comparison table and FAQ (also output as FAQPage schema).
- inc/amazon.php: settings screen, link builders, tag enforcement.
- inc/pages.php: pages created on activation.
- patterns/: hero, trust, picks, styles, compare, otc, method, process, faq, cta, header, footer,
  landing (all sections) and testimonials (not used by default — add real reviews only).
- assets/css/styles.css, assets/js/audira.js, assets/fonts (Fraunces + Inter, SIL OFL 1.1), assets/images.

== Compliance notes ==
- Do not hard-code Amazon prices or star ratings; buttons say "Check price on Amazon".
- The FTC bans fake or invented reviews. Only publish real testimonials, with permission.
- Keep the disclosure bar and footer statement visible.

== Credits ==
Fraunces (The Fraunces Project Authors) and Inter (The Inter Project Authors), SIL Open Font License 1.1.
Logo and illustrations: original artwork created for this theme, GPL-2.0-or-later.

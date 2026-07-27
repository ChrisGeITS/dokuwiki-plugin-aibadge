# Changelog

## [1.0.0] - 2026-07-26
### Added
- Initial Release.

## [1.1.0] - 2026-07-27
### New Features
* **Configuration Manager Support:** Added native configuration settings accessible directly via DokuWiki's Admin Panel (`conf/default.php` and `conf/metadata.php`).
* **Custom Badge Positioning:** Choose between 4 position presets for the AI badge:
  * Top Left (*Default*)
  * Top Right
  * Bottom Left
  * Bottom Right
* **Color & Opacity Customization:**
  * Configurable background color (Hex values, default: `#000000`).
  * Configurable text color (Hex values, default: `#ffffff`).
  * Adjustable transparency slider/percentage (0% to 100%, default: `33%`).
* **Custom Text Override:** Added an admin setting to toggle and set a global custom badge text instead of using automatic translations.
* **Expanded Translations:** Added complete `settings.php` translation files for all 25 supported EU languages, ensuring a fully localized admin interface.
### Improvements & Fixes
* **Theme-Native Typography:** Removed explicit `font-family` declarations so the badge seamlessly adopts the host DokuWiki template's font stack.
* **Improved Layout Handling:** Refactored CSS position selectors to maintain compatibility with DokuWiki image floats (`medialeft`, `mediaright`, `mediacenter`).
* **Clean Code Architecture:** Separated wiki content translations (`lang.php`) from admin configuration descriptions (`settings.php`).
### Backward Compatibility
* **100% Non-Breaking:** All new options default to the original v1.0.0 design (Top Left, Black, 33% opacity, auto-detected language). Existing `<ai>...</ai>` tags on your wiki pages require no changes.
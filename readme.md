# DokuWiki AI Badge Plugin (`aibadge`)

An extension for [DokuWiki](https://www.dokuwiki.org/) to label AI-generated or synthetic media with a visual badge directly on the image.

This plugin helps wiki administrators and content creators clearly label AI-generated or synthetic media to support transparency requirements under **Article 50 of the EU AI Act (AI Regulation)**, which mandates that synthetic content, generated media, and deepfakes must be clearly recognizable as artificially created.

Requires:
- DokuWiki Release: testet on Mort
- Modern browser with CSS :has() support

---

## Features

* **Supports EU AI Act Transparency Requirements:** Easily mark synthetic images to fulfill transparency standards.
* **Full Configuration Manager Support:** Customize badge placement, background color, text color, opacity, and custom text overrides via DokuWiki's native Admin settings panel.
* **Flexible Badge Positioning:** Place the badge in any corner of your images (*Top Left*, *Top Right*, *Bottom Left*, *Bottom Right*).
* **Non-destructive Syntax:** Wraps standard DokuWiki media syntax without altering core media rendering, image paths, alignments, or scaling options.
* **Full Text Flow & Layout Support:** Preserves text wrapping (`float: left` / `float: right`) and center alignment configured via DokuWiki's standard syntax.
* **Multi-language Support:** Automatically displays the badge in the configured wiki language, with native translations for **24 EU official languages plus German informal (de-informal)** and automatic fallback to English. Includes translated settings descriptions for the Admin panel.
* **Lightweight CSS Styling:** Clean, modern overlay designed with customizable transparency, automatically adopting the typography of your active DokuWiki template.

---

## Multilingual Support

The plugin automatically detects your DokuWiki language setting (`$conf['lang']`) and displays the localized badge text on pages, as well as translated setting labels in the Configuration Manager.

Supported languages out of the box:
`bg`, `cs`, `da`, `de`, `de-informal`, `el`, `en`, `es`, `et`, `fi`, `fr`, `ga`, `hr`, `hu`, `it`, `lt`, `lv`, `mt`, `nl`, `pl`, `pt`, `ro`, `sk`, `sl`, `sv`.

---

## Screenshot

<img src="screenshots/example.png" alt="AI Badge Example" width="300">

---

## Example Deployment

An example of this plugin in use can be found at:

- https://paedagogik.wiki

---

## Installation via Extension Manager

1. Open the DokuWiki Extension Manager.
2. Search for "aibadge".
3. Install and enable the plugin.

---

## Manual Installation

1. Download or clone this repository into your DokuWiki plugin directory:
   ```bash
   cd lib/plugins/
   git clone [https://github.com/ChrisGeITS/dokuwiki-plugin-aibadge.git](https://github.com/ChrisGeITS/dokuwiki-plugin-aibadge.git) aibadge
   ```
2. Ensure the directory name under lib/plugins/ is strictly named aibadge.
3. Clear your DokuWiki cache (e.g., by saving a wiki page or appending ?purge=true to a page URL).

---

## Usage

Enclose any standard DokuWiki image syntax inside the <ai>...</ai> tags:

   ```bash
    <ai>
    {{:wiki:example.png?300}}
    </ai>
   ```

Examples
Basic Image with Badge

   ```bash
    <ai>
    {{:wiki:landscape.jpg}}
    </ai>
   ```

---

## Resized Image with Text Flow (Left/Right Alignment)

The plugin fully respects DokuWiki's alignment spacing (leading/trailing spaces in media syntax):

   ```bash
    <ai>
    {{ :wiki:portrait.png?200 | AI Generated Portrait}}
    </ai>
   ```

Text flows smoothly around the image while the badge remains pinned to the upper-left corner of the image itself.


---

## How It Works

Syntax Parsing: The plugin registers a protected syntax pattern for <ai>...</ai>.

Standard Rendering: It delegates the inner content processing back to DokuWiki's native parser (p_render()). This ensures complete compatibility with built-in features such as image resizing, titles, popups, and alignment classes (medialeft, mediaright, mediacenter).

DOM Injection: It wraps the generated <img> element inside a <div class="ai-image-wrapper"> block along with an absolute-positioned <span class="ai-badge">.

CSS Layout Transfer: Using CSS :has() pseudo-classes, the wrapper inherits floating and margin properties directly from DokuWiki's image class, maintaining seamless integration into your wiki layout.

---

## Support

If this Software is useful to you and you would like to support its development, you can buy me a coffee via PayPal:


https://paypal.me/ChristophGenenger

---

## Changelog

See [changelog.md](changelog.md)

---

## License

This project is open-source software licensed under the GPL v2.
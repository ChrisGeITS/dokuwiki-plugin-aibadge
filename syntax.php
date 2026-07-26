<?php
if (!defined('DOKU_INC')) die();

class syntax_plugin_aibadge extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'substition';
    }

    public function getSort() {
        return 195;
    }

    public function connectTo($mode) {
        // Greift verlässlich, sobald irgendwo in {{ ... }} der String ai als Parameter vorkommt
        $this->Lexer->addSpecialPattern('\{\{[^\}]*?\bai\b[^\}]*?\}\}', $mode, 'plugin_aibadge');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // Rohinhalt zwischen {{ und }}
        $raw = substr($match, 2, -2);

        // Titel/Alt-Text abspalten, falls vorhanden (|)
        $parts = explode('|', $raw, 2);
        $src = $parts[0];
        $title = isset($parts[1]) ? $parts[1] : null;

        // Entferne das ai-Flag aus den Parametern:
        // 1. &ai (z. B. ?200&ai)
        // 2. ?ai& (z. B. ?ai&200 -> ?200)
        // 3. ?ai (z. B. ?ai alleinstehend)
        $cleanSrc = preg_replace('/&ai\b/i', '', $src);
        $cleanSrc = preg_replace('/\?ai&/i', '?', $cleanSrc);
        $cleanSrc = preg_replace('/\?ai\b/i', '', $cleanSrc);

        return array(
            'src'   => $cleanSrc,
            'title' => $title
        );
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if ($format !== 'xhtml') return false;

        global $conf;

        // Sprachauswahl: de / de-informel -> KI-generiert, sonst AI-generated
        $currentLang = strtolower($conf['lang'] ?? 'en');
        if (in_array($currentLang, array('de', 'de-informel'), true)) {
            $badgeText = 'KI-generiert';
        } else {
            $badgeText = 'AI-generated';
        }

        // DokuWiki-Standard-Media-Renderer mit allen bereinigten Parametern (z.B. ?200) aufrufen
        $imageHtml = $renderer->_media($data['src'], $data['title']);

        // Wrapper und Badge ausgeben
        $renderer->doc .= '<div class="ai-image-wrapper">';
        $renderer->doc .= $imageHtml;
        $renderer->doc .= '<span class="ai-badge">' . hsc($badgeText) . '</span>';
        $renderer->doc .= '</div>';

        return true;
    }
}
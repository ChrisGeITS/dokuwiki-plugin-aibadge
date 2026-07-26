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
        // Greift sobald ?ai oder &ai irgendwo in der Bild-Syntax auftaucht
        $this->Lexer->addSpecialPattern('\{\{[^\}]*?[\?\&]ai\b[^\}]*?\}\}', $mode, 'plugin_aibadge');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // {{ und }} entfernen und Trimming
        $content = trim(substr($match, 2, -2));

        // Spalte Titel/Alt-Text ab, falls vorhanden (|)
        $parts = explode('|', $content, 2);
        $src = $parts[0];
        $title = isset($parts[1]) ? $parts[1] : '';

        // 1. Spezieller Fall: ?200&ai -> &ai sauber entfernen -> bleibt ?200
        $cleanSrc = preg_replace('/&ai\b/', '', $src);

        // 2. Fall: ?ai&200 -> ?ai& durch ? ersetzen -> bleibt ?200
        $cleanSrc = preg_replace('/\?ai&/', '?', $cleanSrc);

        // 3. Fall: Nur ?ai am Ende -> ?ai komplett entfernen
        $cleanSrc = preg_replace('/\?ai\b/', '', $cleanSrc);

        return array(
            'src'   => $cleanSrc,
            'title' => $title
        );
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if ($format !== 'xhtml') return false;

        global $conf;

        // Sprachprüfung: de / de-informel -> "KI-generiert", sonst "AI-generated"
        $currentLang = strtolower($conf['lang'] ?? 'en');
        if (in_array($currentLang, array('de', 'de-informel'), true)) {
            $badgeText = 'KI-generiert';
        } else {
            $badgeText = 'AI-generated';
        }

        // Standard DokuWiki Media Rendering mit den verbliebenen Parametern (z.B. ?200)
        $imageHtml = $renderer->_media($data['src'], $data['title']);

        // Wrapper & Badge injizieren
        $renderer->doc .= '<div class="ai-image-wrapper">';
        $renderer->doc .= $imageHtml;
        $renderer->doc .= '<span class="ai-badge">' . hsc($badgeText) . '</span>';
        $renderer->doc .= '</div>';

        return true;
    }
}
<?php
if (!defined('DOKU_INC')) die();

class syntax_plugin_aibadge extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'substition';
    }

    public function getSort() {
        // Niedriger als 320 (Standard-Media), damit unser Plugin ZUERST greift
        return 195;
    }

    public function connectTo($mode) {
        // Greift exakt auf {{... ?ai ...}} oder {{... &ai ...}}
        $this->Lexer->addSpecialPattern('\{\{[^\}]*?[\?\&]ai\b[^\}]*?\}\}', $mode, 'plugin_aibadge');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // {{ und }} entfernen
        $content = trim(substr($match, 2, -2));

        // Spalte Titel/Alt-Text ab, falls vorhanden (|)
        $parts = explode('|', $content, 2);
        $src = $parts[0];
        $title = isset($parts[1]) ? $parts[1] : '';

        // Entferne nur den Parameter ?ai oder &ai aus der URL/Syntax
        $cleanSrc = preg_replace('/([\?\&])ai(\b|$)/', '$1', $src);
        // Aufräumen, falls am Ende ein unsauberes ? oder & stehen bleibt
        $cleanSrc = preg_replace('/[\?\&]$/', '', $cleanSrc);

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

        // Standard DokuWiki Media Rendering für das bereinigte Bild aufrufen
        $imageHtml = $renderer->_media($data['src'], $data['title']);

        // Wrapper & Badge injizieren
        $renderer->doc .= '<div class="ai-image-wrapper">';
        $renderer->doc .= $imageHtml;
        $renderer->doc .= '<span class="ai-badge">' . hsc($badgeText) . '</span>';
        $renderer->doc .= '</div>';

        return true;
    }
}
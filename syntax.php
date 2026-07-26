<?php
if (!defined('DOKU_INC')) die();

class syntax_plugin_aibadge extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'substition';
    }

    public function getSort() {
        return 315; // Greift kurz vor dem Standard-Bild-Rendering (320)
    }

    public function connectTo($mode) {
        // Matcht DokuWiki-Bild-Syntax mit ?ai oder &ai
        $this->Lexer->addSpecialPattern('\{\{[^\}]*?[\?\&]ai([^\}]*?)\}\}', $mode, 'plugin_aibadge');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        // Entferne {{ und }}
        $content = substr($match, 2, -2);
        
        // Spalte Bild-Pfad/Parameter und Alt-Text/Titel ab
        list($src, $title) = explode('|', $content, 2);
        
        // Bereinige das Steuerflag ?ai bzw. &ai aus der URL
        $cleanSrc = preg_replace('/([?&])ai(&|$)/', '$1', $src);
        $cleanSrc = rtrim($cleanSrc, '?&');

        return array($cleanSrc, $title);
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if ($format !== 'xhtml') return false;

        global $conf;
        list($src, $title) = $data;

        // Sprachlogik: Prüfe ob die eingestellte Wiki-Sprache de oder de-informel ist
        $currentLang = strtolower($conf['lang'] ?? 'en');
        if (in_array($currentLang, array('de', 'de-informel'), true)) {
            $badgeText = 'KI-generiert';
        } else {
            $badgeText = 'AI-generated';
        }

        // Bild über das DokuWiki-eigene Rendering erzeugen
        $imageHtml = $renderer->_media($src, $title);

        // HTML-Output mit Badge-Container
        $renderer->doc .= '<div class="ai-image-wrapper">';
        $renderer->doc .= $imageHtml;
        $renderer->doc .= '<span class="ai-badge">' . hsc($badgeText) . '</span>';
        $renderer->doc .= '</div>';

        return true;
    }
}
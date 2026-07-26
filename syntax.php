<?php
if (!defined('DOKU_INC')) die();

class syntax_plugin_aibadge extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'protected'; // Behandelt den Inhalt als geschützten Block
    }

    public function getPType() {
        return 'normal';
    }

    public function getSort() {
        return 190;
    }

    public function connectTo($mode) {
        // Matcht das öffnende und schließende <ai> Tag inklusive Inhalt
        $this->Lexer->addEntryPattern('<ai>(?=.*?</ai>)', $mode, 'plugin_aibadge');
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</ai>', 'plugin_aibadge');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                return array($state, '');
            case DOKU_LEXER_UNMATCHED:
                return array($state, $match);
            case DOKU_LEXER_EXIT:
                return array($state, '');
        }
        return array();
    }

    public function render($format, Doku_Renderer $renderer, $data) {
        if ($format !== 'xhtml') return false;

        global $conf;
        list($state, $match) = $data;

        switch ($state) {
            case DOKU_LEXER_ENTER:
                // Sprachprüfung: de / de-informal -> KI-generiert, sonst AI-generated
                $currentLang = strtolower($conf['lang'] ?? 'en');
                if (in_array($currentLang, array('de', 'de-informal'), true)) {
                    $badgeText = 'KI-generiert';
                } else {
                    $badgeText = 'AI-generated';
                }

                // Öffnenden Wrapper und Badge ausgeben
                $renderer->doc .= '<div class="ai-image-wrapper">';
                $renderer->doc .= '<span class="ai-badge">' . hsc($badgeText) . '</span>';
                break;

            case DOKU_LEXER_UNMATCHED:
                // Lässt DokuWiki die Standard-Syntax (Bilder, Links etc.) im Inneren ganz normal rendern
                $renderer->doc .= p_render($format, p_get_instructions($match), $info);
                break;

            case DOKU_LEXER_EXIT:
                // Schließendes Tag für den Wrapper
                $renderer->doc .= '</div>';
                break;
        }

        return true;
    }
}
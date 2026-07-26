<?php
if (!defined('DOKU_INC')) die();

class syntax_plugin_aibadge extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'protected';
    }

    public function getPType() {
        return 'normal';
    }

    public function getSort() {
        return 190;
    }

    public function connectTo($mode) {
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

        list($state, $match) = $data;

        switch ($state) {
            case DOKU_LEXER_ENTER:
                // DokuWiki lädt automatisch die richtige lang.php (auch für de-informal etc.)
                $badgeText = $this->getLang('badge_text');

                // Öffnenden Wrapper und Badge ausgeben
                $renderer->doc .= '<div class="ai-image-wrapper">';
                $renderer->doc .= '<span class="ai-badge">' . hsc($badgeText) . '</span>';
                break;

            case DOKU_LEXER_UNMATCHED:
                $renderer->doc .= p_render($format, p_get_instructions($match), $info);
                break;

            case DOKU_LEXER_EXIT:
                $renderer->doc .= '</div>';
                break;
        }

        return true;
    }
}
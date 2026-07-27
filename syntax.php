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
                // 1. Text ermitteln (Custom vs. Übersetzung)
                if ($this->getConf('custom_text_active') && trim($this->getConf('custom_text')) !== '') {
                    $badgeText = $this->getConf('custom_text');
                } else {
                    $badgeText = $this->getLang('badge_text');
                }

                // 2. Transparenz berechnen (Deckkraft alpha = 1 - (Prozent / 100))
                $transparencyPercent = (int) $this->getConf('opacity');
                $alpha = max(0, min(1, 1 - ($transparencyPercent / 100)));

                // 3. Hex-Farbe in RGBA umwandeln
                $hexColor = $this->getConf('bg_color');
                $rgbaBg = $this->hexToRgba($hexColor, $alpha);

                // 4. Inline-Styles und Position verarbeiten
                $positionClass = 'ai-pos-' . $this->getConf('badge_position');
                $textColor = hsc($this->getConf('text_color'));

                $style = "background-color: {$rgbaBg}; color: {$textColor};";

                // Output des Wrappers und Badges
                $renderer->doc .= '<div class="ai-image-wrapper">';
                $renderer->doc .= '<span class="ai-badge ' . hsc($positionClass) . '" style="' . $style . '">' . hsc($badgeText) . '</span>';
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

    /**
     * Hilfsfunktion: Konvertiert Hex (#000000) zu rgba(0, 0, 0, alpha)
     */
    private function hexToRgba($hex, $alpha) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "rgba($r, $g, $b, $alpha)";
    }
}
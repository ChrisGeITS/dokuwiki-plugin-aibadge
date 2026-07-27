<?php
$meta['badge_position']     = array('multichoice', '_choices' => array('top-left', 'top-right', 'bottom-left', 'bottom-right'));
$meta['bg_color']           = array('string', '_pattern' => '/^#([a-fA-F0-9]{3}){1,2}$/');
$meta['text_color']         = array('string', '_pattern' => '/^#([a-fA-F0-9]{3}){1,2}$/');
$meta['opacity']            = array('numeric', '_min' => 0, '_max' => 100);
$meta['custom_text_active'] = array('onoff');
$meta['custom_text']        = array('string');
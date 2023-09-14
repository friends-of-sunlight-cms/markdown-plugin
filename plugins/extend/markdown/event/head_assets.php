<?php

use Sunlight\Core;
use Sunlight\Settings;
use Sunlight\Template;

return function (array $args) {
    $config = $this->getConfig();
    $dark = $config['dark_mode'];
    $args['css'][] = $this->getAssetPath('public/css/markdown-' . ($dark ? 'dark' : 'light') . '.css');
};

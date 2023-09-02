<?php

use Sunlight\Core;
use Sunlight\Settings;
use Sunlight\Template;

return function (array $args) {
    $config = $this->getConfig();
    $dark = $config['dark_mode'];

    if ($dark === null) {
        if (Core::$env === Core::ENV_ADMIN) {
            $dark = Settings::get('adminscheme_dark');
        } else {
            $dark = Template::getCurrent()->getOption('dark');
        }
    }

    $args['css'][] = $this->getAssetPath('public/css/markdown-' . ($dark ? 'dark' : 'light') . '.css');
};

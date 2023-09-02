<?php

use Sunlight\Page\Page;
use Sunlight\WebState;

return function (array $args) {
    global $_index, $_page;

    if (!$this->getConfig()['parse_pages']
        || $_index->type !== WebState::PAGE
        || $_page['type'] != Page::SECTION
    ) {
        return;
    }

    $parsedown = new Parsedown();
    $parsedown->setSafeMode($this->getConfig()['safe_mode']);

    $args['page']['content'] = '<div class="markdown-body">' . $parsedown->text($args['page']['content']) . '</div>';
};

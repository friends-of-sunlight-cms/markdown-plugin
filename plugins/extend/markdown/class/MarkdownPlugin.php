<?php

namespace SunlightExtend\Markdown;

use Parsedown;
use Sunlight\Core;
use Sunlight\Page\Page;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Settings;
use Sunlight\Template;
use Sunlight\WebState;

class MarkdownPlugin extends ExtendPlugin
{
    public function onHead(array $args): void
    {
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
    }

    public function onPageParse(array $args): void
    {
        global $_index, $_page;

        if (!$this->getConfig()['parse_pages']
            || $_index->type !== WebState::PAGE
            || $_page['type'] != Page::SECTION
        ) {
            return;
        }
        $args['page']['content'] = $this->parseMarkdown($args['page']['content']);
    }

    /**
     * @param string $content content for Markdown parser
     * @param bool $safeMode processing untrusted user-input (escaping, ...)
     */
    private function parseMarkdown(string $content, bool $safeMode = false): string
    {
        $parsedown = new Parsedown();
        if ($safeMode) {
            $parsedown->setSafeMode(true);
        }
        return '<div class="markdown-body">' . $parsedown->text($content) . '</div>';
    }
}
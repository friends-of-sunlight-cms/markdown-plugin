<?php

namespace SunlightExtend\Markdown;

use Parsedown;
use Sunlight\Core;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Settings;
use Sunlight\Template;

class MarkdownPlugin extends ExtendPlugin
{

    /**
     * @sl-event tpl.head
     * @param array $args
     */
    public function onHead(array $args): void
    {
        $config = $this->getConfig();

        // Register CSS only if parsing is enabled for at least one type.
        if ($config['parse_posts'] || $config['parse_pages']) {
            $dark = $config['dark_mode'];

            if ($dark === null) {
                if (Core::$env === Core::ENV_ADMIN) {
                    $dark = Settings::get('adminscheme_dark');
                } else {
                    $dark = Template::getCurrent()->getOption('dark');
                }
            }

            $args['css'][] = $this->getWebPath() . '/Resources/css/github-markdown' . ($dark ? '-dark' : '-light') . '.css';
        }
    }

    /**
     * @sl-event post.parse
     * @param array $args
     */
    public function onPostParse(array $args): void
    {
        if (!$this->getConfig()['parse_posts']) {
            return;
        }
        //$args['nl2br'] = false;
        $args['content'] = $this->parseMarkdown($args['content'], true);
    }

    /**
     * @sl-event tpl.content
     * @param array $args
     */
    public function onPageParse(array $args): void
    {
        if (!$this->getConfig()['parse_pages']) {
            return;
        }
        $args['content'] = $this->parseMarkdown($args['content']);
    }

    /**
     * @param string $content content for markdown parser
     * @param bool $safeMode processing untrusted user-input (escaping, ...)
     * @return string
     */
    private function parseMarkdown(string $content, bool $safeMode = false): string
    {
        $parsedown = new Parsedown();
        if($safeMode){
            $parsedown->setSafeMode(true);
        }
        return '<div class="markdown-body">' . $parsedown->text($content) . '</div>';
    }

    public function getConfigDefaults(): array
    {
        return [
            'dark_mode' => 'null',
            'parse_pages' => true,
            'parse_posts' => false,
        ];
    }

    function getAction(string $name): ?PluginAction
    {
        if ($name === 'config') {
            return new ConfigAction($this);
        }
        return parent::getAction($name);
    }

}

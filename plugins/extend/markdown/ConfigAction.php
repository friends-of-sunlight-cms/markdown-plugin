<?php

namespace SunlightExtend\Markdown;

use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;
use Sunlight\Util\Json;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        return [
            'dark_mode' => [
                'label' => _lang('markdown.cfg.dark_mode'),
                'input' => _buffer(function () use ($config) { ?>
                    <select name="config[dark_mode]">
                        <option value="" <?= $config['dark_mode'] === null ? ' selected' : '' ?>><?= _lang('lightbox.cfg.dark_mode.auto') ?></option>
                        <option value="1" <?= $config['dark_mode'] === true ? ' selected' : '' ?>><?= _lang('global.yes') ?></option>
                        <option value="0" <?= $config['dark_mode'] === false ? ' selected' : '' ?>><?= _lang('global.no') ?></option>
                    </select>
                    <?php }),
            ],
            'parse_posts' => [
                'label' => _lang('markdown.cfg.parse_posts'),
                'input' => '<input type="checkbox" name="config[parse_posts]" value="1"' . Form::activateCheckbox($config->offsetGet('parse_posts')) . '>',
                'type' => 'checkbox'
            ],
            'parse_pages' => [
                'label' => _lang('markdown.cfg.parse_pages'),
                'input' => '<input type="checkbox" name="config[parse_pages]" value="1"' . Form::activateCheckbox($config->offsetGet('parse_pages')) . '>',
                'type' => 'checkbox'
            ],
            'parse_bbcodes' => [
                'label' => _lang('markdown.cfg.parse_bbcodes'),
                'input' => '<input type="checkbox" name="config[parse_bbcodes]" value="1"' . Form::activateCheckbox($config->offsetGet('parse_bbcodes')) . '>',
                'type' => 'checkbox'
            ],

        ];
    }

    protected function mapSubmittedValue(ConfigurationFile $config, string $key, array $field, $value): ?string
    {
        switch ($key) {
            case 'dark_mode':
                $config[$key] = ($value === '' ? null : (bool) $value);
                return null;
        }

        return parent::mapSubmittedValue($config, $key, $field, $value);
    }
}

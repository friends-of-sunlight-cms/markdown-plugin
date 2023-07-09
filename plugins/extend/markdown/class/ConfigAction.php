<?php

namespace SunlightExtend\Markdown;

use Fosc\Feature\Plugin\Config\FieldGenerator;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        $langPrefix = "%p:markdown.config";

        $gen = new FieldGenerator($this->plugin);
        $gen->field([
            'dark_mode' => [
                'label' => _lang('markdown.config.dark_mode'),
                'input' => _buffer(function () use ($config) { ?>
                    <select name="config[dark_mode]">
                        <option value="" <?= Form::selectOption($config['dark_mode'] === null) ?>><?= _lang('markdown.config.dark_mode.auto') ?></option>
                        <option value="1" <?= Form::selectOption($config['dark_mode'] === true) ?>><?= _lang('global.yes') ?></option>
                        <option value="0" <?= Form::selectOption($config['dark_mode'] === false) ?>><?= _lang('global.no') ?></option>
                    </select>
                <?php })
            ]
        ])
            ->generateField('parse_pages', $langPrefix, '%checkbox');

        return $gen->getFields();
    }

    protected function mapSubmittedValue(ConfigurationFile $config, string $key, array $field, $value): ?string
    {
        if ($key === 'dark_mode') {
            $config[$key] = ($value === '' ? null : (bool)$value);
            return null;
        }

        return parent::mapSubmittedValue($config, $key, $field, $value);
    }
}

<?php

namespace Actcmscss;

class ActcmscssBladeDirectives
{
    public static function this()
    {
        return "window.actcmscss.find('{{ \$_instance->id }}')";
    }

    public static function entangle($expression)
    {
        return <<<EOT
<?php if ((object) ({$expression}) instanceof \Actcmscss\WireDirective) : ?>window.Actcmscss.find('{{ \$_instance->id }}').entangle('{{ {$expression}->value() }}'){{ {$expression}->hasModifier('defer') ? '.defer' : '' }}<?php else : ?>window.Actcmscss.find('{{ \$_instance->id }}').entangle('{{ {$expression} }}')<?php endif; ?>
EOT;
    }

    public static function actcmscssStyles($expression)
    {
        return '{!! \Actcmscss\Actcmscss::styles('.$expression.') !!}';
    }

    public static function actcmscssScripts($expression)
    {
        return '{!! \Actcmscss\Actcmscss::scripts('.$expression.') !!}';
    }

    public static function actcmscss($expression)
    {
        $lastArg = str(last(explode(',', $expression)))->trim();

        if ($lastArg->startsWith('key(') && $lastArg->endsWith(')')) {
            $cachedKey = $lastArg->replaceFirst('key(', '')->replaceLast(')', '');
            $args = explode(',', $expression);
            array_pop($args);
            $expression = implode(',', $args);
        } else {
            $cachedKey = "'".str()->random(7)."'";
        }

        return <<<EOT
<?php
if (! isset(\$_instance)) {
    \$html = \Actcmscss\Actcmscss::mount({$expression})->html();
} elseif (\$_instance->childHasBeenRendered($cachedKey)) {
    \$componentId = \$_instance->getRenderedChildComponentId($cachedKey);
    \$componentTag = \$_instance->getRenderedChildComponentTagName($cachedKey);
    \$html = \Actcmscss\Actcmscss::dummyMount(\$componentId, \$componentTag);
    \$_instance->preserveRenderedChild($cachedKey);
} else {
    \$response = \Actcmscss\Actcmscss::mount({$expression});
    \$html = \$response->html();
    \$_instance->logRenderedChild($cachedKey, \$response->id(), \Actcmscss\Actcmscss::getRootElementTagName(\$html));
}
echo \$html;
?>
EOT;
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Widget\WidgetManager;
use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolver;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WidgetExtension extends AbstractExtension
{
    public function __construct(
        private WidgetManager $widgetManager,
        private TemplateResolver $templateResolver,
        private Environment $twigEnvironment,
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('widget', [$this, 'renderWidget'], ['is_safe' => ['html']]),
        ];
    }

    public function renderWidget($type, $options = [], $resource = null): string
    {
        $widget = $this->widgetManager->getWidget($type, $options);
        $data = $widget->createViewData($resource);
        $template = $this->templateResolver->resolve($widget->getTemplate());
        $content = $this->twigEnvironment->render($template, $data);

        return $content;
    }
}

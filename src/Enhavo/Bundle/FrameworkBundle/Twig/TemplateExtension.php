<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\Twig;

use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class TemplateExtension extends AbstractExtension
{
    /**
     * TemplateExtension constructor.
     */
    public function __construct(
        private TemplateResolver $templateResolver
    )
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('template', [$this, 'getTemplate']),
            new TwigFunction('create_array', [$this, 'createArray']),
        ];
    }

    /**
     * @return mixed
     */
    public function getTemplate(string $template): string
    {
        return $this->templateResolver->resolve($template);
    }

    public function createArray($key, $data)
    {
        return [$key => $data];
    }
}

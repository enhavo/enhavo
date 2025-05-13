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

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class TemplateExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    /**
     * @var string[]
     */
    private $formThemes;

    /**
     * @var TemplateResolver
     */
    private $templateResolver;

    /**
     * TemplateExtension constructor.
     */
    public function __construct(array $formThemes, TemplateResolver $templateResolver)
    {
        $this->formThemes = $formThemes;
        $this->templateResolver = $templateResolver;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('template', [$this, 'getTemplate']),
            new TwigFunction('form_themes', [$this, 'getFormThemes']),
            new TwigFunction('create_array', [$this, 'createArray']),
        ];
    }

    public function getFormThemes()
    {
        return $this->formThemes;
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

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Templating\EngineInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SearchForm extends AbstractExtension
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('search_form', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    public function render($type = 'search', $entities = null, $fields = null)
    {
        if (null === $this->templateEngine) {
            $this->templateEngine = $this->container->get('twig');
        }

        $template = $this->container->getParameter('enhavo_search.'.$type.'.template');

        return $this->templateEngine->render($template, [
            'type' => $type,
            'entities' => $entities,
            'fields' => $fields,
        ]);
    }

    public function getName()
    {
        return 'search_render';
    }
}

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

use Pagerfanta\Pagerfanta;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twigEnvironment;

    /**
     * @var string
     */
    private $template;

    /**
     * PaginationRender constructor.
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function setTwigEnvironment(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('pagination_render', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    public function render(Pagerfanta $pagination, array $parameters = [])
    {
        return $this->twigEnvironment->render($this->template, array_merge([
            'pagination' => $pagination,
        ], $parameters));
    }

    public function getName()
    {
        return 'pagination_render';
    }
}

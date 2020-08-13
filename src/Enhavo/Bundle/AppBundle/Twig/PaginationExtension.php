<?php

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
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }

    /**
     * @param Environment $twigEnvironment
     */
    public function setTwigEnvironment(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('pagination_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(Pagerfanta $pagination, array $parameters = [])
    {
        return $this->twigEnvironment->render($this->template, array_merge([
            'pagination' => $pagination
        ], $parameters));
    }

    public function getName()
    {
        return 'pagination_render';
    }
}

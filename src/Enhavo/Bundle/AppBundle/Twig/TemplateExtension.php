<?php
/**
 * Template.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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
     * @param array $formThemes
     * @param TemplateResolver $templateResolver
     */
    public function __construct(array $formThemes, TemplateResolver $templateResolver)
    {
        $this->formThemes = $formThemes;
        $this->templateResolver = $templateResolver;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('template', array($this, 'getTemplate')),
            new TwigFunction('form_themes', array($this, 'getFormThemes')),
            new TwigFunction('create_array', array($this, 'createArray')),
        );
    }

    public function getFormThemes()
    {
        return $this->formThemes;
    }

    /**
     * @param $template
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

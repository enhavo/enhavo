<?php
/**
 * Template.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

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
     * TemplateExtension constructor.
     * @param array $formThemes
     */
    public function __construct(array $formThemes)
    {
        $this->formThemes = $formThemes;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('template', array($this, 'getTemplate')),
            new TwigFunction('form_themes', array($this, 'getFormThemes')),
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
        $templates = $this->container->getParameter('enhavo_app.template');
        if(!array_key_exists($template, $templates)) {
            throw new \InvalidArgumentException(sprintf('Template "%s" does not exists. Did you mean one of them [%s]',
                $template,
                implode(',', $templates)
            ));
        }
        return $templates[$template];
    }
}

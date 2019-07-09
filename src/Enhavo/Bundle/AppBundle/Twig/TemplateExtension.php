<?php
/**
 * Template.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
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
     * @var TemplateManager
     */
    private $templateManager;

    /**
     * TemplateExtension constructor.
     * @param array $formThemes
     * @param TemplateManager $templateManager
     */
    public function __construct(array $formThemes, TemplateManager $templateManager)
    {
        $this->formThemes = $formThemes;
        $this->templateManager = $templateManager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('template', array($this, 'getTemplate')),
            new TwigFunction('form_themes', array($this, 'getFormThemes')),
            new TwigFunction('webpack_build', array($this, 'getWebpackBuild')),
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
        return $this->templateManager->getTemplate($template);
    }

    public function getWebpackBuild()
    {
        return $this->templateManager->getWebpackBuild();
    }
}

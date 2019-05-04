<?php
/**
 * ThemeTemplate.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ThemeTemplate extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Template constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('theme_template', array($this, 'getTemplate')),
        );
    }

    /**
     * @return string
     */
    public function getTemplate($template)
    {
        $templates = $this->container->getParameter('enhavo_theme.template');
        if(!array_key_exists($template, $templates)) {
            throw new \InvalidArgumentException(sprintf('Template "%s" does not exists. Did you mean one of them [%s]',
                $template,
                implode(',', $templates)
            ));
        }
        return $templates[$template];
    }

    public function getName()
    {
        return 'theme_template';
    }
}
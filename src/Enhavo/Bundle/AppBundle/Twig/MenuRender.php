<?php
/**
 * MenuRenderer.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Menu\Menu;
use Enhavo\Bundle\AppBundle\Type\TypeFactory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuRender extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @var TypeFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $config;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var string
     */
    private $template;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    public function __construct(TypeFactory $factory, AuthorizationCheckerInterface $checker, $template, $config)
    {
        $this->factory = $factory;
        $this->template = $template;
        $this->config = $config;
        $this->checker = $checker;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('menu_render', array($this, 'render'), array('is_safe' => array('html'))),
        ];
    }

    public function render($template = null)
    {
        if($template === null) {
            $template = $this->template;
        }

        $menus = [];
        foreach($this->config as $name => $options) {
            if(!isset($options['type'])) {
                throw new TypeMissingException(sprintf('No type was given for menu with name "%s"', $name));
            }

            /** @var Menu $menu */
            $menu = $this->factory->create($options['type'], $options);

            if($menu->isHidden()) {
                continue;
            }

            if($menu->getPermission() !== null && !$this->checker->isGranted($menu->getPermission())) {
                continue;
            }

            $menus[] = $menu;
        }

        return $this->container->get('templating')->render($template, [
            'menus' => $menus
        ]);
    }
}
<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Enhavo\Bundle\AppBundle\Menu\Menu;
use Enhavo\Bundle\AppBundle\Type\TypeFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ListMenu extends AbstractMenu
{
    public function render(array $options)
    {
        $template = $options['template'];
        $translationDomain = $options['translationDomain'];
        $icon = $options['icon'];
        $label = $options['label'];
        $menuConfig = $options['menu'];

        $menus = $this->getMenus($menuConfig);

        $active = false;
        foreach($menus as $menu) {
            if($menu->isActive()) {
                $active = true;
                break;
            }
        }

        return $this->renderTemplate($template, [
            'label' => $label,
            'translationDomain' => $translationDomain,
            'icon' => $icon,
            'menus' => $menus,
            'active' => $active
        ]);
    }
    
    public function isHidden(array $options)
    {
        $isHidden = parent::isHidden($options);
        if(!$isHidden) {
            $menus = $this->getMenus($options['menu']);
            return count($menus) == 0;
        }
        return $isHidden;
    }

    /**
     * @return TypeFactory
     */
    private function getFactory()
    {
        return $this->container->get('enhavo_app.menu.factory');
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    private function getSecurityChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    /**
     * @param $menuConfig
     * @return Menu[]
     * @throws TypeMissingException
     */
    private function getMenus($menuConfig)
    {
        $menus = [];
        foreach($menuConfig as $name => $options) {
            /** @var Menu $menu */
            $menu = $this->getFactory()->create($options);

            if($menu->isHidden()) {
                continue;
            }

            if($menu->getPermission() !== null && !$this->getSecurityChecker()->isGranted($menu->getPermission())) {
                continue;
            }

            $menus[] = $menu;
        }
        return $menus;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'EnhavoAppBundle:Menu:list.html.twig',
            'translationDomain' => null,
            'icon' => ''
        ]);

        $resolver->setRequired([
            'label',
            'menu'
        ]);
    }

    public function getType()
    {
        return 'list';
    }
}
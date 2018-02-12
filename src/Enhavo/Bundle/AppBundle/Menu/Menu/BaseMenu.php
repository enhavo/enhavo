<?php
/**
 * BaseMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;

class BaseMenu extends AbstractMenu
{
    public function render(array $options)
    {
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:Menu:base.html.twig');
        $translationDomain = $this->getOption('translationDomain', $options, null);
        $icon = $this->getOption('icon', $options, null);
        $class = $this->getOption('class', $options, '');

        $label = $this->getRequiredOption('label', $options);
        $route = $this->getRequiredOption('route', $options);

        $current = false;

        return $this->renderTemplate($template, [
            'label' => $label,
            'translationDomain' => $translationDomain,
            'icon' => $icon,
            'route' => $route,
            'class' => $class,
            'current' => $current
        ]);
    }

    public function getType()
    {
        return 'base';
    }
}
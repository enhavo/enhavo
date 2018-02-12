<?php
/**
 * BaseMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseMenu extends AbstractMenu
{
    public function render(array $options)
    {
        $template = $options['template'];
        $translationDomain = $options['translationDomain'];
        $icon = $options['icon'];
        $class = $options['class'];

        $label = $options['label'];
        $route = $options['route'];

        $active = $this->isActive($options);

        return $this->renderTemplate($template, [
            'label' => $label,
            'translationDomain' => $translationDomain,
            'icon' => $icon,
            'route' => $route,
            'class' => $class,
            'active' => $active
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'template' => 'EnhavoAppBundle:Menu:base.html.twig',
            'translationDomain' => null,
            'icon' => null,
            'class' => '',
        ]);

        $resolver->setRequired([
            'label',
            'route'
        ]);
    }

    public function getType()
    {
        return 'base';
    }
}
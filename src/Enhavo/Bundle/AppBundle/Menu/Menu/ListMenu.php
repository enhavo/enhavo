<?php
/**
 * ListMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Enhavo\Bundle\AppBundle\Type\TypeFactory;

class ListMenu extends AbstractMenu
{
    public function render(array $options)
    {
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:Menu:list.html.twig');
        $translationDomain = $this->getOption('translation_domain', $options, null);
        $icon = $this->getOption('icon', $options, null);

        $label = $this->getRequiredOption('label', $options);

        return $this->renderTemplate($template, [
            'label' => $label,
            'translationDomain' => $translationDomain,
            'icon' => $icon
        ]);
    }

    /**
     * @return TypeFactory
     */
    private function getFactory()
    {
        return $this->container->get('enhavo_app.menu.factory');
    }

    public function getType()
    {
        return 'list';
    }
}
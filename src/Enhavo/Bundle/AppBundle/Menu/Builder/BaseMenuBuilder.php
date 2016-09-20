<?php
/**
 * BaseMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Builder;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuBuilder;

class BaseMenuBuilder extends AbstractMenuBuilder
{
    public function createMenu(array $options)
    {
        parent::createMenu($options);
        $name = 'base';
        if(isset($options['name'])) {
            $name = $options['name'];
        }

        $menu =  $this->getFactory()->createItem($name);

        $translationDomain = null;
        if(isset($options['translationDomain'])) {
            $translationDomain = $options['translationDomain'];
        }

        if(isset($options['label'])) {
            $menu->setLabel($this->getTranslator()->trans($options['label'], [], $translationDomain));
        }

        if(isset($options['route'])) {
            $menu->setUri($this->getRouter()->generate($options['route']));
        }

        if(isset($options['icon'])) {
            $menu->setIcon($options['icon']);
        }

        return $menu;
    }

    protected function getTranslator()
    {
        return $this->container->get('translator');
    }

    public function getType()
    {
        return 'base';
    }
}
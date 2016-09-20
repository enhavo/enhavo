<?php
/**
 * MainMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Builder;

class MainMenuBuilder extends ListMenuBuilder
{
    public function createMenu(array $options)
    {
        if(!isset($options['name'])) {
            $options['name'] = 'enhavo_main';
        }
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'enhavo_main';
    }
}
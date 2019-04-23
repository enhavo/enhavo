<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
 */

namespace Enhavo\Bundle\NavigationBundle\Item\Configuration;

use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\NavigationBundle\Factory\SubmenuFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\SubmenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmenuConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'form' => SubmenuType::class,
            'label' => 'node.label.submenu',
            'translation_domain' => 'EnhavoNavigationBundle',
            'type' => 'submenu',
            'factory' => SubmenuFactory::class,
        ]);
    }

    public function getType()
    {
        return 'submenu';
    }
}
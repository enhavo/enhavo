<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem\Type;

use Enhavo\Bundle\NavigationBundle\Entity\Submenu;
use Enhavo\Bundle\NavigationBundle\Factory\SubmenuFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\SubmenuType;
use Enhavo\Bundle\NavigationBundle\NavItem\AbstractNavItemType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmenuNavItemType extends AbstractNavItemType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form' => SubmenuType::class,
            'label' => 'node.label.submenu',
            'translation_domain' => 'EnhavoNavigationBundle',
            'factory' => SubmenuFactory::class,
            'model' => Submenu::class
        ]);
    }

    public static function getName(): ?string
    {
        return 'submenu';
    }
}

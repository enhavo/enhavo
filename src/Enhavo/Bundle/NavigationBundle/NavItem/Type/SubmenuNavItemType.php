<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
            'template' => 'theme/navigation/submenu.html.twig',
            'factory' => SubmenuFactory::class,
            'model' => Submenu::class,
        ]);
    }

    public static function getName(): ?string
    {
        return 'submenu';
    }
}

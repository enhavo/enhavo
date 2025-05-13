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

use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Factory\LinkFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\LinkType;
use Enhavo\Bundle\NavigationBundle\NavItem\AbstractNavItemType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkNavItemType extends AbstractNavItemType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => Link::class,
            'factory' => LinkFactory::class,
            'form' => LinkType::class,
            'template' => 'theme/navigation/link.html.twig',
            'label' => 'link.label.link',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);
    }

    public static function getName(): ?string
    {
        return 'link';
    }
}

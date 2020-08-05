<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
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
            'template' => 'EnhavoNavigationBundle:Navigation:link.html.twig',
            'label' => 'link.label.link',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);
    }

    public static function getName(): ?string
    {
        return 'link';
    }
}

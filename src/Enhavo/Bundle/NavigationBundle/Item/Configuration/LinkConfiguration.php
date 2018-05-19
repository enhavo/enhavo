<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
 */

namespace Enhavo\Bundle\NavigationBundle\Item\Configuration;

use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\NavigationBundle\Factory\LinkFactory;
use Enhavo\Bundle\NavigationBundle\Form\Type\LinkType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'model' => Link::class,
            'form' => LinkType::class,
            'factory' => LinkFactory::class,
            'label' => 'Link',
            'template' => 'EnhavoNavigationBundle:Form:link.html.twig',
            'options' => []
        ]);
    }

    public function getType()
    {
        return 'link';
    }
}
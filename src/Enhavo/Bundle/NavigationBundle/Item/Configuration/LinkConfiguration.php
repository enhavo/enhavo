<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:53
 */

namespace Enhavo\Bundle\NavigationBundle\Item\Configuration;

use Enhavo\Bundle\NavigationBundle\Entity\Link;
use Enhavo\Bundle\NavigationBundle\Form\Type\LinkConfigurationType;
use Enhavo\Bundle\NavigationBundle\Form\Type\LinkType;
use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\NavigationBundle\Factory\LinkFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'content_model' => Link::class,
            'content_factory' => LinkFactory::class,
            'content_form' => LinkType::class,
            'configuration_form' => LinkConfigurationType::class,
            'label' => 'link.label.link',
            'translationDomain' => 'EnhavoNavigationBundle',
            'options' => []
        ]);
    }

    public function getType()
    {
        return 'link';
    }
}
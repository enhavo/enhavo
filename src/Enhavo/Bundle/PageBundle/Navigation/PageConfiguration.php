<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.05.18
 * Time: 15:30
 */

namespace Enhavo\Bundle\PageBundle\Navigation;

use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\PageBundle\Form\Type\NavigationPageNodeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'page.label.page',
            'translationDomain' => 'EnhavoPageBundle',
            'form' => NavigationPageNodeType::class,
            'template' => 'EnhavoPageBundle:Navigation:page.html.twig',
        ]);
    }

    public function getType()
    {
        return 'page';
    }
}
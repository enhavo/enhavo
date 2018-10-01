<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.05.18
 * Time: 15:30
 */

namespace Enhavo\Bundle\PageBundle\Navigation;

use Enhavo\Bundle\NavigationBundle\Item\AbstractConfiguration;
use Enhavo\Bundle\PageBundle\Form\Type\NavigationPageConfigurationType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'page.label.page',
            'translationDomain' => 'EnhavoPageBundle',
            'configuration_form' => NavigationPageConfigurationType::class,
            'content_form' => 'enhavo_page_page_choice',
            'render_template' => 'EnhavoPageBundle:Navigation:page.html.twig',
            'content_factory' => null
        ]);
    }

    public function getType()
    {
        return 'page';
    }
}
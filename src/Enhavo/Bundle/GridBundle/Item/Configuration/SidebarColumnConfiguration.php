<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:26
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\GridBundle\Model\Column\SidebarColumnItem;
use Enhavo\Bundle\GridBundle\Factory\SidebarColumnItemFactory;
use Enhavo\Bundle\GridBundle\Form\Type\SidebarColumnItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SidebarColumnConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => SidebarColumnItem::class,
            'parent' => SidebarColumnItem::class,
            'form' => SidebarColumnItemType::class,
            'factory' => SidebarColumnItemFactory::class,
            'repository' => 'EnhavoGridBundle:SidebarColumn',
            'template' => 'EnhavoGridBundle:Item:sidebar-column.html.twig',
            'form_template' => 'EnhavoGridBundle:Form:item_fields.html.twig',
            'label' => 'sidebar_column.label.sidebar_column',
            'translationDomain' => 'EnhavoGridBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public function getType()
    {
        return 'sidebar_column';
    }
}
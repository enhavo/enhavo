<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:26
 */

namespace Enhavo\Bundle\SidebarBundle\Item;

use Enhavo\Bundle\SidebarBundle\Entity\SidebarColumnItem;
use Enhavo\Bundle\SidebarBundle\Factory\SidebarColumnItemFactory;
use Enhavo\Bundle\SidebarBundle\Form\Type\SidebarColumnItemType;
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
            'repository' => 'EnhavoSidebarBundle:SidebarColumn',
            'template' => 'EnhavoSidebarBundle:Item:sidebar-column.html.twig',
            'form_template' => 'EnhavoGridBundle:Form:item_fields.html.twig',
            'label' => 'sidebar_column.label.sidebar_column',
            'translationDomain' => 'EnhavoSidebarBundle',
            'groups' => ['default', 'layout']
        ]);
    }

    public function getType()
    {
        return 'sidebar_column';
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:26
 */

namespace Enhavo\Bundle\SidebarBundle\Block;

use Enhavo\Bundle\SidebarBundle\Entity\SidebarColumnBlock;
use Enhavo\Bundle\SidebarBundle\Factory\SidebarColumnBlockFactory;
use Enhavo\Bundle\SidebarBundle\Form\Type\SidebarColumnBlockType;
use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SidebarColumnConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => SidebarColumnBlock::class,
            'parent' => SidebarColumnBlock::class,
            'form' => SidebarColumnBlockType::class,
            'factory' => SidebarColumnBlockFactory::class,
            'repository' => 'EnhavoSidebarBundle:SidebarColumn',
            'template' => 'EnhavoSidebarBundle:Block:sidebar-column.html.twig',
            'form_template' => 'EnhavoBlockBundle:Form:block_fields.html.twig',
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

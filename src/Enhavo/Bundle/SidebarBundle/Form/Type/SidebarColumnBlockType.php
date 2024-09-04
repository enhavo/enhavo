<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:33
 */

namespace Enhavo\Bundle\SidebarBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\ColumnType;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\SidebarBundle\Entity\Sidebar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Enhavo\Bundle\SidebarBundle\Entity\SidebarColumnBlock;

class SidebarColumnBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sidebar', EntityType::class,[
            'class' => Sidebar::class,
            'label' => 'sidebar.label.sidebar',
            'translation_domain' => 'EnhavoSidebarBundle',
            'choice_label' => 'name',
            'placeholder' => '---'
        ]);

        $builder->add('column', BlockNodeType::class, [
            'label' => 'column.label.column',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => ['content'],
            'prototype' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SidebarColumnBlock::class
        ]);
    }

    public function getParent()
    {
        return ColumnType::class;
    }
}

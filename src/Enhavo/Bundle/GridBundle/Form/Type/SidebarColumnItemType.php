<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 30.05.19
 * Time: 16:33
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Model\Column\OneColumnItem;
use Enhavo\Bundle\SidebarBundle\Entity\Sidebar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Enhavo\Bundle\GridBundle\Model\Column\SidebarColumnItem;

class SidebarColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sidebar', EntityType::class,[
            'class' => Sidebar::class,
            'label' => 'sidebar.label.sidebar',
            'translation_domain' => 'EnhavoSidebarBundle',
            'choice_label' => 'name'
        ]);
        $builder->add('column', GridType::class, [
            'label' => 'column.label.column',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SidebarColumnItem::class
        ]);
    }

    public function getParent()
    {
        return ColumnType::class;
    }
}

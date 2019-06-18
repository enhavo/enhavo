<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 19:22
 */

namespace Enhavo\Bundle\SidebarBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\ContainerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class SidebarType extends AbstractType
{
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'sidebar.label.name',
            'translation_domain' => 'EnhavoSidebarBundle',
        ]);
        $builder->add('code', TextType::class, [
            'label' => 'sidebar.label.code',
            'translation_domain' => 'EnhavoSidebarBundle',
        ]);
        $builder->add('content', ContainerType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
            'item_groups' => ['content'],
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }
}

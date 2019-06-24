<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 19:22
 */

namespace Enhavo\Bundle\TemplateBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;

class TemplateType extends AbstractType
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'template.label.name',
            'translation_domain' => 'EnhavoTemplateBundle',
        ]);
        $builder->add('code', TextType::class, [
            'label' => 'template.label.code',
            'translation_domain' => 'EnhavoTemplateBundle',
        ]);
        $builder->add('content', BlockNodeType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
            'item_groups' => ['layout'],
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }
}

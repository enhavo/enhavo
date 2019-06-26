<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 19:22
 */

namespace Enhavo\Bundle\TemplateBundle\Form\Type;

use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
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
        $builder->add('content', BlockNodeType::class, array(
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
            'item_groups' => ['layout', 'template'],
        ));

        $builder->add('route', RouteType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults( array(
            'data_class' => $this->class
        ));
    }
}

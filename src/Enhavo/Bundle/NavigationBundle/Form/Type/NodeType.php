<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 17:55
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\FormBundle\Form\Type\TypeNameType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    /** @var string */
    private $class;

    /**
     * NodeType constructor.
     *
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', PositionType::class);
        $builder->add('name', TypeNameType::class);

        $builder->add('label', TextType::class, [
            'label' => 'node.label.label',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('subject',  $options['subject_type'], $options['subject_type_options']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'subject_type_options' => []
        ]);

        $resolver->setRequired('subject_type');
    }

    public function getBlockPrefix()
    {
        return 'enhavo_navigation_node';
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 17:55
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DynamicItemType;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    /**
     * @var string
     */
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
        $builder->add('position', PositionType::class, []);

        $builder->add('label', TextType::class, [
            'label' => 'node.label.label',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        if($options['configuration_type']) {
            $builder->add('configuration',  $options['configuration_type'], $options['configuration_type_options']);
        }

        if($options['content_type']) {
            $builder->add('content',  $options['content_type'], $options['content_type_options']);
        }

        if($options['children']) {
            $builder->add('children', NodesType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Node::class,
            'children' => false,
            'block_name' => 'enhavo_dynamic_item',
            'item_resolver' => 'enhavo_navigation.resolver.node_resolver',
            'configuration_type' => null,
            'configuration_type_options' => [],
            'content_type' => null,
            'content_type_options' => [],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_navigation_node';
    }

    public function getParent()
    {
        return DynamicItemType::class;
    }
}
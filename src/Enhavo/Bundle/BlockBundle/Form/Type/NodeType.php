<?php
/**
 * BlockType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\FormBundle\Form\Type\TypeNameType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('position', PositionType::class);
        $builder->add('name', TypeNameType::class);
        $builder->add('block', $options['block_type'], $options['block_type_options']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Node::class,
            'block_type_options' => [],
            'component' => 'form-block-node',
        ));

        $resolver->setRequired(['block_type']);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_node';
    }
}

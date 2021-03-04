<?php
/**
 * ContainerType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockNodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('children', BlockCollectionType::class, [
            'item_groups' => $options['item_groups'],
            'items' => $options['items'],
            'add_label' => $options['add_label'],
            'translation_domain' => $options['translation_domain']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Node::class,
            'label' => 'block.label.container',
            'translation_domain' => 'EnhavoBlockBundle',
            'item_groups' => [],
            'items' => [],
            'add_label' => '',
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_node';
    }
}

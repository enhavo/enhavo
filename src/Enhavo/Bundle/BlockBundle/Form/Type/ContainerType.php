<?php
/**
 * ContainerType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Entity\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('blocks', BlocksType::class, [
            'entry_type' => BlockType::class,
            'block_groups' => $options['block_groups'],
            'blocks' => $options['blocks']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Container::class,
            'label' => 'block.label.container',
            'translation_domain' => 'EnhavoBlockBundle',
            'block_groups' => [],
            'blocks' => []
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_container';
    }
}

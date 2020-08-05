<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 17:22
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Mock\Form;

use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\BlockBundle\Tests\Mock\Model\Column;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnBlockMockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('column', BlockNodeType::class, [
            'label' => 'Column'
        ]);

        $builder->add('text', BlockNodeType::class, [
            'label' => 'Text'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Column::class]);
    }
}

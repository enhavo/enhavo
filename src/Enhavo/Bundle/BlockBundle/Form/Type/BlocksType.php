<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.05.18
 * Time: 18:42
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\DynamicFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocksType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'block.label.blocks',
            'translation_domain' => 'EnhavoBlockBundle',
            'block_resolver' => 'enhavo_block.resolver.block_resolver',
            'block_route' => 'enhavo_block_block_form',
            'block_class' => $this->class,
        ]);
    }

    public function getParent()
    {
        return DynamicFormType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_blocks';
    }
}



<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08/06/14
 * Time: 16:32
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enhavo_position';
    }

    public function getParent()
    {
        return 'hidden';
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'position'
            ]
        ));
    }
} 
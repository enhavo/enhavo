<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29/05/16
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'enhavo_datetime';
    }

    public function getParent()
    {
        return DateType::class;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
        ));
    }
} 
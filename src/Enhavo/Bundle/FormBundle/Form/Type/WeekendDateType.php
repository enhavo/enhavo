<?php
/**
 * @author blutze
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeekendDateType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'enhavo_date';
    }

    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\DateType::class;
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
            'format' => 'dd.MM.yyyy',
            'attr' => [
                'data-weekend-date-picker' => null,
                'autocomplete' => 'off'
            ]
        ));
    }
}

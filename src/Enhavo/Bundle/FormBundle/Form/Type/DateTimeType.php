<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29/05/16
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeType extends AbstractType
{
    /**
     * @var array|null
     */
    private $defaultDateTimePickerOptions;

    /**
     * @param $defaultDateTimePickerOptions
     */
    public function __construct($defaultDateTimePickerOptions)
    {
        $this->defaultDateTimePickerOptions = $defaultDateTimePickerOptions;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('data-date-time-picker', $options['attr'])) {
            if ($options['datetimepicker_options'] === null) {
                $dateTimePickerOptions = $this->defaultDateTimePickerOptions;
            } else {
                $dateTimePickerOptions = array_merge($this->defaultDateTimePickerOptions, $options['datetimepicker_options']);
            }
            $view->vars['attr']['data-date-time-picker'] = json_encode($dateTimePickerOptions);
        }
    }

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
            'datetimepicker_options' => null,
            'attr' => [
                'data-date-time-picker' => null,
                'autocomplete' => 'off'
            ]
        ));
    }
} 

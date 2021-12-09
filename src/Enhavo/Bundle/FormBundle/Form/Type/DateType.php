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

class DateType extends AbstractType
{
    /**
     * @var array|null
     */
    private $defaultDateTimePickerConfig;

    /**
     * @param string $defaultDateTimePickerConfig
     */
    public function __construct($defaultDateTimePickerConfig)
    {
        $this->defaultDateTimePickerConfig = $defaultDateTimePickerConfig;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('data-date-picker', $options['attr'])) {
            $view->vars['attr']['data-date-picker'] = $options['config'];
        }
        if (!$options['allow_typing']) {
            $view->vars['attr']['readonly'] = 'readonly';
        }
        $view->vars['allowClear'] = $options['allow_clear'];
    }

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
            'config' => $this->defaultDateTimePickerConfig,
            'allow_typing' => false,
            'allow_clear' => false,
            'attr' => [
                'data-date-picker' => null,
                'autocomplete' => 'off'
            ]
        ));
    }
}

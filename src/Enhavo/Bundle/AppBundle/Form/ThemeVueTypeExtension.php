<?php

namespace Enhavo\Bundle\AppBundle\Form;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThemeVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private readonly array $mappings,
    )
    {
    }

    public function finishVueData(FormView $view, VueData $data, array $options)
    {
        if (array_key_exists($options['component_theme'], $this->mappings)) {
            $this->changeValues($view, $data, $options['component_theme']);
        }
    }

    private function changeValues(FormView $view, VueData $data, $theme): void
    {
        if (array_key_exists($data->get('component'), $this->mappings[$theme])) {
            $data->set('component', $this->mappings[$theme][$data->get('component')]);
        }

        if (array_key_exists($data->get('rowComponent'), $this->mappings[$theme])) {
            $data->set('rowComponent', $this->mappings[$theme][$data->get('rowComponent')]);
        }

        foreach ($view->children as $child) {
            if (isset($child->vars['vue_data'])) {
                $this->changeValues($child, $child->vars['vue_data'], $theme);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['component_theme' => null]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class, ButtonType::class];
    }
}

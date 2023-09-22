<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private TranslatorInterface $translator,
        private NormalizerInterface $normalizer,
    )
    {
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['name'] = $view->vars['name'];
        $data['value'] = $this->normalizer->normalize($view->vars['value'], null, ['groups' => ['vue-form']]);
        $data['compound'] = $view->vars['compound'];
        $data['id'] = $view->vars['id'];
        $data['required'] = $view->vars['required'];
        $data['fullName'] = $view->vars['full_name'];
        $data['help'] = $view->vars['help'];
        $data['helpAttr'] = $view->vars['help_attr'];
        $data['helpHtml'] = $view->vars['help_html'];

        $errors = [];
        foreach ($view->vars['errors'] as $error) {
            $errors[] = [
                'message' => $error->getMessage()
            ];
        }
        $data['errors'] = $errors;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'row_component' => 'form-row',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}

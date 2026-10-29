<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Enhavo\Bundle\TranslationBundle\Form\Locale\FormLocaleResolverInterface;
use Enhavo\Bundle\TranslationBundle\Form\Transformer\TranslationValueTransformer;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractVueType
{
    public function __construct(
        private readonly TranslationManager $translationManager,
        private readonly FormLocaleResolverInterface $formLocaleResolver,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new TranslationValueTransformer($this->translationManager, $this->formLocaleResolver, $options['translation_data'], $options['translation_property']));

        foreach ($this->formLocaleResolver->getLocales() as $locale) {
            $builder->add($locale, $options['form_type'], $options['form_options']);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var FormErrorIterator $errors */
        $errors = $view->vars['errors'];

        $newErrors = [];
        /** @var FormError $error */
        foreach ($errors as $error) {
            if (!preg_match('/^\(.+\) /', $error->getMessage())) {
                $newErrors[] = new FormError(
                    sprintf('(%s) %s', $this->formLocaleResolver->getDefaultLocale(), $error->getMessage()),
                    $error->getMessageTemplate(),
                    $error->getMessageParameters(),
                    $error->getMessagePluralization(),
                    $error->getCause()
                );
            } else {
                $newErrors[] = $error;
            }
        }

        $help = $form->get($this->formLocaleResolver->getDefaultLocale())->getConfig()->getOption('help');
        $view->vars['help'] = $help;

        $view->vars['errors'] = new FormErrorIterator($errors->getForm(), $newErrors);
        $view->vars['translation_locales'] = $this->formLocaleResolver->getLocales();

        parent::buildView($view, $form, $options);
    }

    public function buildVueData(FormView $view, VueData $data, array $options): void
    {
        $data->set('translationLocales', $view->vars['translation_locales']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'constraints' => [
            ],
            'row_component' => 'form-translation',
        ]);

        $resolver->setRequired([
            'translation_data',
            'translation_property',
            'form_options',
            'form_type',
        ]);
    }
}

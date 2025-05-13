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

use Enhavo\Bundle\TranslationBundle\Form\Transformer\TranslationValueTransformer;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    /** @var TranslationManager */
    private $translationManager;

    /**
     * TranslationType constructor.
     */
    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TranslationValueTransformer($this->translationManager, $options['translation_data'], $options['translation_property']));

        foreach ($this->translationManager->getLocales() as $locale) {
            $builder->add($locale, $options['form_type'], $options['form_options']);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var FormErrorIterator $errors */
        $errors = $view->vars['errors'];

        $newErrors = [];
        /** @var FormError $error */
        foreach ($errors as $error) {
            if (!preg_match('/^\(.+\) /', $error->getMessage())) {
                $newErrors[] = new FormError(
                    sprintf('(%s) %s', $this->translationManager->getDefaultLocale(), $error->getMessage()),
                    $error->getMessageTemplate(),
                    $error->getMessageParameters(),
                    $error->getMessagePluralization(),
                    $error->getCause()
                );
            } else {
                $newErrors[] = $error;
            }
        }

        $help = $form->get($this->translationManager->getDefaultLocale())->getConfig()->getOption('help');
        $view->vars['help'] = $help;

        $view->vars['errors'] = new FormErrorIterator($errors->getForm(), $newErrors);
        $view->vars['translation_locales'] = $this->translationManager->getLocales();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'constraints' => [
            ],
        ]);

        $resolver->setRequired([
            'translation_data',
            'translation_property',
            'form_options',
            'form_type',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_translation_translation';
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-23
 * Time: 01:35
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Type;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var TranslationManager
     */
    private $translationManager;

    /**
     * TranslationType constructor.
     * @param FormFactory $formFactory
     * @param TranslationManager $translationManager
     */
    public function __construct(FormFactory $formFactory, TranslationManager $translationManager)
    {
        $this->formFactory = $formFactory;
        $this->translationManager = $translationManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FormInterface $child */
        $form = $options['form'];
        $formData = $options['form_data'];
        $translationManager = $this->translationManager;

        $builder->addModelTransformer(new CallbackTransformer(
            function ($originalDescription) use($translationManager, $form, $formData) {
                $data = [
                    $translationManager->getDefaultLocale() => $originalDescription,
                ];

                $translations = $translationManager->getTranslations($formData, $form->getName());
                foreach($translations as $locale => $value) {
                    $data[$locale] = $value;
                }

                return $data;
            },
            function ($submittedDescription) use($translationManager, $form, $formData) {
                $data = $submittedDescription;
                foreach($data as $locale => $value) {
                    if($locale !== $translationManager->getDefaultLocale()) {
                        $translationManager->setTranslation($formData, $form->getName(), $locale, $value);
                    }
                }
                return $data[$translationManager->getDefaultLocale()];
            }
        ));

        foreach($translationManager->getLocales() as $locale) {
            $builder->add($locale, $options['form_type'], $form->getConfig()->getOptions());
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var FormErrorIterator $errors */
        $errors = $view->vars['errors'];

        $newErrors= [];
        /** @var FormError $error */
        foreach($errors as $error) {
            if(!preg_match('/^\(.+\) /', $error->getMessage())) {
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

        $view->vars['errors'] = new FormErrorIterator($errors->getForm(), $newErrors);
        $view->vars['translation_locales'] = $this->translationManager->getLocales();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling' => false,
            'constraints' => [
                new Translation([
                    'groups' => ['default']
                ])
            ]
        ]);

        $resolver->setRequired(['form', 'form_type', 'form_data']);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_translation_tranlsation';
    }
}

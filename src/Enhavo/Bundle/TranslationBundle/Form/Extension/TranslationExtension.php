<?php
/**
 * TranslationExtension.php
 *
 * @since 07/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class TranslationExtension extends AbstractTypeExtension
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param Translator $translator
     * @param string $defaultLocale
     */
    public function __construct(Translator $translator, $defaultLocale)
    {
        $this->translator = $translator;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([
            'translation'
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationData = null;

        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use (&$translationData) {
            $form = $event->getForm();
            $data = $event->getData();

            $parent = $form->getParent();
            if($parent instanceof Form) {
                $propertyPath = (string)$form->getPropertyPath();
                $entity = $parent->getData();

                if($entity === null) {
                    $entity = $parent->getConfig()->getDataClass();
                }

                if(!$this->translator->isPropertyTranslatable($entity, $propertyPath)) {
                    return;
                }

                $translationData = $this->translator->normalizeToTranslationData($entity, $propertyPath, $data);
                $data = $this->translator->normalizeToModelData($entity, $propertyPath, $data);

                $event->setData($data);
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use (&$translationData) {
            $form = $event->getForm();

            $parent = $form->getParent();
            if($parent instanceof Form) {
                $propertyPath = (string)$form->getPropertyPath();
                $entity = $parent->getData();

                if(!$this->translator->isPropertyTranslatable($entity, $propertyPath)) {
                    return;
                }

                $this->translator->addTranslationData($entity, $propertyPath, $translationData);
            }
        });

    }

    /**
     * Pass the image URL to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['translation'] = isset($options['translation']) && $options['translation'] === true;
        $view->vars['currentLocale'] = $this->defaultLocale;
        if($view->vars['translation']) {
            $parent = $form->getParent();
            if($parent instanceof Form) {
                $property = new Property($form->getPropertyPath());
                $entity = $parent->getConfig()->getDataClass();
                if(is_object($parent->getData())) {
                    $entity = $parent->getData();
                }

                $translations = $this->translator->getTranslationData($entity, $property);
                if($translations === null) {
                    $view->vars['translation'] = false;
                    return;
                }
                $view->vars['translations'] = $translations;
            }
        }
    }
}
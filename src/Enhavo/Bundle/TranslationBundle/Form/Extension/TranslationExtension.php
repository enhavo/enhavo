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
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;

abstract class TranslationExtension extends AbstractTypeExtension
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
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

        if($view->vars['translation']) {
            $parent = $form->getParent();

            if($parent instanceof Form && is_object($parent->getData())) {
                $property = new Property($form->getPropertyPath());
                $view->vars['translations'] = $this->translator->getTranslations($parent->getData(), $property);
            }
        }
    }
}
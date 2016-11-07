<?php
/**
 * TextareaExtension.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;

class TextareaExtension extends AbstractTypeExtension
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
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return TextareaType::class;
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

        $parent = $form->getParent();

        if($parent instanceof Form && is_object($parent->getData())) {
            $property = new Property($form->getPropertyPath());
            $view->vars['translations'] = $this->translator->getTranslations($parent->getData(), $property);
        }
    }
}
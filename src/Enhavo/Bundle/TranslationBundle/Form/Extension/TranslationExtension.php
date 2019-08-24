<?php
/**
 * TranslationExtension.php
 *
 * @since 07/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\TranslationBundle\Form\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;

class TranslationExtension extends AbstractTypeExtension
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if($data instanceof Article) {
                $child = null;
                $property = 'title';
                foreach($form->all() as $key => $child) {
                    if($child->getName() == $property) {
                        $form->remove($property);
                        break;
                    }
                }

                if($child) {
                    $form->add($property, TranslationType::class, [
                        'child' => $child
                    ]);
                }
            }
        });
    }

    public function getExtendedTypes()
    {
        return [FormType::class];
    }
}

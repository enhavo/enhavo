<?php
/**
 * TranslationExtension.php
 *
 * @since 07/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;

class TranslationExtension extends AbstractTypeExtension
{
    /**
     * @var Translator
     */
    private $translationManager;

    /**
     * DoctrineSubscriber constructor.
     *
     * @param TranslationManager $translationManager
     */
    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(!$this->translationManager->isTranslation()) {
            return;
        }

        $view->vars['translation_locales'] = $this->translationManager->getLocales();
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$this->translationManager->isEnabled()) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            if($this->translationManager->isTranslatable($data)) {
                $translations = [];
                foreach($form->all() as $key => $child) {
                    if($this->translationManager->isTranslatable($data, $key)) {
                        $form->remove($key);
                        $translations[$key] = $child;
                    }
                }

                foreach($translations as $key => $child) {
                    $form->add($key, TranslationType::class, [
                        'form' => $child,
                        'form_data' => $data,
                        'form_type' => $this->translationManager->getFormType($data, $key)
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

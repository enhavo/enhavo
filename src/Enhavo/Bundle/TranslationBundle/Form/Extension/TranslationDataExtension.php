<?php
/**
 * TranslationDataExtension.php
 *
 * @since 21/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Enhavo\Bundle\TranslationBundle\Translator\Translator;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TranslationDataExtension extends AbstractTypeExtension
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use (&$translationData) {
            $form = $event->getForm();
            $entity = $event->getData();

            if(!is_object($entity)) {
                return;
            }

            $metadata = $this->translator->getMetadata($entity);

            if($metadata === null) {
                return;
            }

            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($metadata->getProperties() as $property) {
                if(!$form->has($property->getName()) && !$form->has($property->getUnderscoreName())) {
                    continue;
                }

                $formData = $accessor->getValue($entity, $property->getName());

                $translationData = $this->translator->normalizeToTranslationData($entity, $property->getName(), $formData);
                $this->translator->addTranslationData($entity, $property->getName(), $translationData);
                $normalizeData = $this->translator->normalizeToModelData($entity, $property->getName(), $formData);

                $accessor->setValue($entity, $property->getName(), $normalizeData);
            }
        });
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
<?php
/**
 * SettingType.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Contracts\Translation\TranslatorInterface;

class SettingType extends AbstractType
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * SettingType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @var Setting $settingObject
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $this->translator;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use($translator)
        {
            $form = $event->getForm();
            /** @var Setting $settingEntity */
            $settingEntity = $event->getData();

            $form->add('name', TextType::class, [
                'mapped' => false,
                'data' => $translator->trans($settingEntity->getLabel(), [], $settingEntity->getTranslationDomain()),
                'attr' => [
                    'readonly' => true
                ]
            ]);

            $form->add('value', ValueType::class, [
                'key' => $settingEntity->getKey()
            ]);
        });
    }
}

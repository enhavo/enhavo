<?php
/**
 * SettingType.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\AppBundle\Form\Type\DateType;
use Enhavo\Bundle\AppBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Enhavo\Bundle\ShopBundle\Form\Type\CurrencyType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class SettingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @var Setting $settingObject
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)
        {
            $form = $event->getForm();
            /** @var Setting $settingObject */
            $settingObject = $event->getData();

            $form->add('name', TextType::class, [
                //'read_only' => true,
                'mapped' => false,
                'data' => $settingObject->getLabel(),
                'translation_domain' => $settingObject->getTranslationDomain()
            ]);

            $type = $settingObject->getType();
            if ($type === Setting::SETTING_TYPE_TEXT) {
                $form->add('value', TextType::class, array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_BOOLEAN) {
                $form->add('value', Boolean::class, array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_FILE) {
                $form->add('file', MediaType::class, array(
                    'label' => 'setting.label.file',
                    'translation_domain' => 'EnhavoSettingBundle',
                    'multiple' => false
                ));
            }
            if ($type === Setting::SETTING_TYPE_FILES) {
                $form->add('files', MediaType::class, array(
                    'label' => 'setting.label.files',
                    'translation_domain' => 'EnhavoSettingBundle',
                    'multiple' => true
                ));
            }
            if ($type === Setting::SETTING_TYPE_WYSIWYG) {
                $form->add('value', WysiwygType::class, array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_DATE) {
                $form->add('date', DateType::class, array(
                    'label' => 'setting.label.date',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_DATETIME) {
                $form->add('date', DateTimeType::class, array(
                    'label' => 'setting.label.date',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_CURRENCY) {
                $form->add('value', CurrencyType::class, array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enhavo_setting_setting';
    }
}
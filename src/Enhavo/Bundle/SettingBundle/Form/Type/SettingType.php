<?php
/**
 * SettingType.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Enhavo\Bundle\SettingBundle\Entity\Setting;
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

            $form->add('name', 'text', [
                'read_only' => true,
                'mapped' => false,
                'data' => $settingObject->getLabel(),
                'translation_domain' => $settingObject->getTranslationDomain()
            ]);

            $type = $settingObject->getType();
            if ($type === Setting::SETTING_TYPE_TEXT) {
                $form->add('value', 'text', array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_BOOLEAN) {
                $form->add('value', 'enhavo_boolean', array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_FILE) {
                $form->add('file', 'enhavo_files', array(
                    'label' => 'setting.label.file',
                    'translation_domain' => 'EnhavoSettingBundle',
                    'multiple' => false
                ));
            }
            if ($type === Setting::SETTING_TYPE_FILES) {
                $form->add('files', 'enhavo_files', array(
                    'label' => 'setting.label.files',
                    'translation_domain' => 'EnhavoSettingBundle',
                    'multiple' => true
                ));
            }
            if ($type === Setting::SETTING_TYPE_WYSIWYG) {
                $form->add('value', 'enhavo_wysiwyg', array(
                    'label' => 'setting.label.value',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_DATE) {
                $form->add('date', 'enhavo_date', array(
                    'label' => 'setting.label.date',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_DATETIME) {
                $form->add('date', 'enhavo_datetime', array(
                    'label' => 'setting.label.date',
                    'translation_domain' => 'EnhavoSettingBundle',
                ));
            }
            if ($type === Setting::SETTING_TYPE_CURRENCY) {
                $form->add('value', 'enhavo_currency', array(
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
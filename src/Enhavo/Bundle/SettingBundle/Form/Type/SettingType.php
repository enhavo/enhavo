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
    public function __construct()
    {

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @var Setting $settingObject
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $form = $formFactory->create('enhavo_setting_setting', $setting);
//        $form->setData($settingFactory->create());
//        $form->submit($request);
//        $setting= $form->getData();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $settingObject = $event->getData();
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
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enhavo_setting_setting';
    }

    public function getFormType() // TODO why?
    {
        $name = $this->container->get('request')->get('name');
        return $this->settingService->resolve($name)->getFormType();
    }

    protected function composeCreateForm($builder){
        $builder->add('label', 'text', array(
            'label' => 'setting.label.label',
            'translation_domain' => 'EnhavoSettingBundle',
        ));
        $builder->add('key', 'text', array(
            'label' => 'setting.label.key',
            'translation_domain' => 'EnhavoSettingBundle',
        ));
        $builder->add('type', 'enhavo_list', array(
            'label' => 'setting.label.type',
            'translation_domain' => 'EnhavoSettingBundle',
        ));
        $builder->add('value', 'text', array(
            'label' => 'setting.label.value',
            'translation_domain' => 'EnhavoSettingBundle',
        ));
        $builder->add('file', 'enhavo_files', array(
            'label' => 'setting.label.file',
            'translation_domain' => 'EnhavoSettingBundle',
            'multiple' => false
        ));
        $builder->add('files', 'enhavo_files', array(
            'label' => 'setting.label.files',
            'translation_domain' => 'EnhavoSettingBundle',
            'multiple' => true
        ));
    }

    protected function composeUpdateForm($builder, $settingType){
        if ($settingType === 'text') {
            $builder->add('value', 'text', array(
                'label' => 'setting.label.value',
                'translation_domain' => 'EnhavoSettingBundle',
            ));
        }
        if ($settingType === 'boolean') {
            $builder->add('value', 'enhavo_boolean', array(
                'label' => 'setting.label.value',
                'translation_domain' => 'EnhavoSettingBundle',
            ));
        }
        if ($settingType === 'file') {
            $builder->add('file', 'enhavo_files', array(
                'label' => 'setting.label.file',
                'translation_domain' => 'EnhavoSettingBundle',
                'multiple' => false
            ));
        }
        if ($settingType === 'files') {
            $builder->add('files', 'enhavo_files', array(
                'label' => 'setting.label.file',
                'translation_domain' => 'EnhavoSettingBundle',
                'multiple' => true
            ));
        }
    }
}
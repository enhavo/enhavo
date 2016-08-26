<?php
/**
 * SettingType.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', 'text', array(
            'label' => 'label.label'
        ));
        $builder->add('key', 'text', array(
            'label' => 'label.key'
        ));
        $builder->add('type', 'text', array(
            'label' => 'label.type'
        ));
        $builder->add('value', 'text', array(
            'label' => 'label.value'
        ));
        $builder->add('translation_domain', 'text', array(
            'label' => 'label.translation_domain'
        ));
        $builder->add('file', 'enhavo_files', array(
            'label' => 'label.file',
            'translation_domain' => 'EnhavoSettingBundle',
            'multiple' => false
        ));
        $builder->add('files', 'enhavo_files', array(
            'label' => 'label.file',
            'translation_domain' => 'EnhavoSettingBundle',
            'multiple' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enhavo_setting_setting';
    }

    public function getFormType()
    {
        $name = $this->container->get('request')->get('name');
        return $this->settingService->resolve($name)->getFormType();
    }
}
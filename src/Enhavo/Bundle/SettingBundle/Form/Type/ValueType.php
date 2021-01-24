<?php

namespace Enhavo\Bundle\SettingBundle\Form\Type;

use Enhavo\Bundle\SettingBundle\Model\ValueAccessInterface;
use Enhavo\Bundle\SettingBundle\Setting\SettingManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueType extends AbstractType
{
    /** @var SettingManager */
    private $settingManager;

    /**
     * SettingType constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'value',
            $this->settingManager->getFormType($options['key']),
            $this->settingManager->getFormTypeOptions($options['key'])
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ValueAccessInterface::class
        ]);

        $resolver->setRequired([
           'key'
        ]);
    }
}

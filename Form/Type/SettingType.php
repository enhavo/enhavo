<?php
/**
 * SettingType.php
 *
 * @since 06/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\SettingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use esperanto\SettingBundle\Service\SettingService;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class SettingType extends AbstractType
{
    protected $settingService;

    protected $container;

    public function __construct($container)
    {
        $this->settingService = $container->get('esperanto_setting.setting_service');
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this->getFormType();
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formType) {
            $event->getForm()->add('container', $formType);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'esperanto_setting_setting';
    }

    public function getFormType()
    {
        $name = $this->container->get('request')->get('name');
        return $this->settingService->resolve($name)->getFormType();
    }
}
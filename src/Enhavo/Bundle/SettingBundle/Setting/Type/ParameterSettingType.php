<?php

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterSettingType extends AbstractSettingType
{
    use ContainerAwareTrait;

    public function init(array $options)
    {
        // nothing to do here
    }

    public function getValue(array $options)
    {
        return $this->container->getParameter($options['key']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['key']);
    }
}

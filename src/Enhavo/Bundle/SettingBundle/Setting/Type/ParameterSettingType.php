<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SettingBundle\Setting\Type;

use Enhavo\Bundle\SettingBundle\Setting\AbstractSettingType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParameterSettingType extends AbstractSettingType
{
    use ContainerAwareTrait;

    public function init(array $options, $key = null)
    {
        // nothing to do here
    }

    public function getValue(array $options, $key = null)
    {
        return $this->container->getParameter($options['key']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['key']);
    }
}

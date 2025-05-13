<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle;

use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Enhavo\Bundle\VueFormBundle\Form\VueFormAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoVueFormBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(VueFormAwareInterface::class)
            ->addMethodCall('setVueForm', [new Reference(VueForm::class)])
        ;
    }
}

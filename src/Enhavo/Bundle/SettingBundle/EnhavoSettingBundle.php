<?php

namespace Enhavo\Bundle\SettingBundle;

use Enhavo\Bundle\SettingBundle\Setting\Setting;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoSettingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('Setting', 'enhavo_setting.setting', Setting::class)
        );
    }
}

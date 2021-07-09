<?php

namespace Enhavo\Bundle\TranslationBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler\LocaleProviderAliasCompilerPass;
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('Translation', 'enhavo_translation.translation', Translation::class)
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_translation.auto_generator.route_generator_collector', 'enhavo_translation.locale_generator')
        );

        $container->addCompilerPass(new LocaleProviderAliasCompilerPass());
    }
}

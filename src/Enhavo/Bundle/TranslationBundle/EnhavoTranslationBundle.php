<?php

namespace Enhavo\Bundle\TranslationBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_translation.translation_collector', 'enhavo.translation')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_translation.auto_generator.route_generator_collector', 'enhavo_translation.locale_generator')
        );
    }
}

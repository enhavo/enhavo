<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler\LocaleProviderAliasCompilerPass;
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
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

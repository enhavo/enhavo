<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\MediaBundle\DependencyInjection\Compiler\MediaCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MediaCompilerPass());

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_media.extension_collector', 'enhavo.media_extension')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_media.filter_collector', 'enhavo.media_filter')
        );
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\ContentBundle\DependencyInjection\CompilerPass\StructuredDataTransformerCompilerPass;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformerInterface;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataContainer;

class EnhavoContentBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new StructuredDataTransformerCompilerPass());

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_content.sitemap_collector', 'enhavo.sitemap_collector')
        );

        $container->addCompilerPass(new \Enhavo\Component\Type\TypeCompilerPass('StructuredData', 'enhavo_content.structured_data', StructuredDataContainer::class));

        $container
            ->registerForAutoconfiguration(StructuredDataTypeInterface::class)
            ->addTag('enhavo_content.structured_data');

        $container
            ->registerForAutoconfiguration(StructuredDataTransformerInterface::class)
            ->addTag('enhavo_content.structured_data_transformer');
    }
}

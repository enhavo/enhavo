<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\DependencyInjection;

use Enhavo\Bundle\TranslationBundle\DependencyInjection\EnhavoTranslationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoTranslationExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $extension = new EnhavoTranslationExtensionChild();
        $extension->prepend($container);
        $config = $container->getExtensionConfig('enhavo_translation');
        $config = array_pop($config);
        $config = array_merge($config, [
            'metadata' => [
                'className1' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ]);
        $extension->load([
            'enhavo_translation' => $config,
        ], $container);

        $param = $container->getParameter('enhavo_translation.metadata');

        $this->assertEquals([
            'className1' => [
                'properties' => [
                    'name' => [
                        'type' => 'text',
                    ],
                ],
            ],
        ], $param);
    }
}

class EnhavoTranslationExtensionChild extends EnhavoTranslationExtension
{
    public const CONFIG_DIR = __DIR__.'/../../Resources/config/app';
}

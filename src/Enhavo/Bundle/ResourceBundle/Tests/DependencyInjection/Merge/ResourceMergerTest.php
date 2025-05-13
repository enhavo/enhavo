<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\DependencyInjection\Merge;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\ResourceMerger;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use PHPUnit\Framework\TestCase;

class ResourceMergerTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new ResourceMergerDependencies();

        return $dependencies;
    }

    public function createInstance(ResourceMergerDependencies $dependencies)
    {
        $instance = new ResourceMerger(
        );

        return $instance;
    }

    public function testMergeWithPriority()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $configs = [];

        $configs[] = [
            'resources' => [
                'enhavo_one.hello' => [
                    'priority' => 10,
                    'classes' => [
                        'model' => 'OneHello10',
                    ],
                ],
            ],
        ];

        $configs[] = [
            'resources' => [
                'enhavo_one.hello' => [
                    'priority' => 0,
                    'classes' => [
                        'model' => 'OneHello0',
                    ],
                ],
            ],
        ];

        $configs[] = [
            'resources' => [
                'enhavo_two.hello' => [
                    'priority' => 0,
                    'classes' => [
                        'model' => 'TwoHello0',
                    ],
                ],
            ],
        ];

        $configs = $instance->performMerge($configs);

        $this->assertCount(4, $configs);
        $config = $configs[3]['resources']['enhavo_one.hello'];
        $this->assertEquals([
            'classes' => [
                'model' => 'OneHello10',
                'repository' => EntityRepository::class,
                'factory' => Factory::class,
            ],
        ], $config);
    }

    public function testSetDefaultRepositoryAndFactory()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $configs = [];

        $configs[] = [
            'resources' => [
                'enhavo_one.hello' => [
                    'classes' => [
                        'model' => 'OneHello',
                    ],
                ],
            ],
        ];

        $configs = $instance->performMerge($configs);

        $this->assertCount(2, $configs);
        $config = $configs[1]['resources']['enhavo_one.hello'];
        $this->assertEquals([
            'classes' => [
                'model' => 'OneHello',
                'repository' => EntityRepository::class,
                'factory' => Factory::class,
            ],
        ], $config);
    }
}

class ResourceMergerDependencies
{
}

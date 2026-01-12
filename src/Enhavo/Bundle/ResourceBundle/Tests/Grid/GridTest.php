<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Grid;

use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new GridDependencies();

        return $dependencies;
    }

    public function createInstance(GridDependencies $dependencies)
    {
        $instance = new Grid(
        );

        return $instance;
    }

    public function testNestedConfigureOptions()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $optionResolver = new OptionsResolver();
        $instance->configureOptions($optionResolver);
        $options = $optionResolver->resolve([
            'resource' => 'app.test'
        ]);

        $this->assertIsArray($options['routes']);
    }
}

class GridDependencies
{

}

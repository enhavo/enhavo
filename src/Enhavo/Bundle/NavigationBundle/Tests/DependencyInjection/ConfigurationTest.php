<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-29
 * Time: 13:12
 */

namespace Enhavo\Bundle\NavigationBundle\Tests\DependencyInjection;

use Enhavo\Bundle\NavigationBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testVoterDefault()
    {
        $configuration = new Configuration();
        $config = $this->process($configuration, []);
        $this->assertCount(0, $config['voters']);
    }
}

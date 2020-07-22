<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-06
 * Time: 19:48
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Tests\DependencyInjection;

use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private function process(Configuration $configuration, array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration($configuration, $configs);
    }

    public function testProviderRequired()
    {
        $this->expectException(InvalidConfigurationException::class);
        $configuration = new Configuration();
        $this->process($configuration, [[]]);
    }
}

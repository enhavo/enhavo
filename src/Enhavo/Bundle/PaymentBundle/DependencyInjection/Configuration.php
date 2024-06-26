<?php

namespace Enhavo\Bundle\PaymentBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\PaymentBundle\Entity\GatewayConfig;
use Enhavo\Bundle\PaymentBundle\Entity\PaymentSecurityToken;
use Enhavo\Bundle\PaymentBundle\Factory\GatewayConfigFactory;
use Enhavo\Bundle\PaymentBundle\Form\Type\GatewayConfigType;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enhavo_payment');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);
        $this->addPaymentSection($rootNode);
        $this->addCurrenciesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('payment_security_token')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(PaymentSecurityToken::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('gateway_config')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(GatewayConfig::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(GatewayConfigFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(GatewayConfigType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addPaymentSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('payment')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('methods')
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('form')->isRequired()->end()
                                    ->scalarNode('gateway_factory')->isRequired()->end()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->scalarNode('translation_domain')->defaultValue(null)->end()
                                    ->scalarNode('enabled')->defaultValue(true)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addCurrenciesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('currencies')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }
}

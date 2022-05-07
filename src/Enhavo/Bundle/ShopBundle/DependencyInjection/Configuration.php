<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ShopBundle\Entity\Voucher;
use Enhavo\Bundle\ShopBundle\Factory\ProductVariantProxyFactory;
use Enhavo\Bundle\ShopBundle\Factory\VoucherFactory;
use Enhavo\Bundle\ShopBundle\Form\Type\VoucherType;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxy;
use Enhavo\Bundle\ShopBundle\Repository\VoucherRepository;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_shop');
        $rootNode = $treeBuilder->getRootNode();

        $this->addDocumentSection($rootNode);
        $this->addPaymentSection($rootNode);
        $this->addResourcesSection($rootNode);
        $this->addProductSection($rootNode);

        return $treeBuilder;
    }

    private function addDocumentSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('document')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('billing')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('generator')->defaultValue('enhavo_shop.document.billing_generator')->end()
                                ->variableNode('options')->end()
                            ->end()
                        ->end()
                        ->arrayNode('packing_slip')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('generator')->defaultValue('enhavo_shop.document.packing_slip_generator')->end()
                                ->variableNode('options')->end()
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
                        ->arrayNode('paypal')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('logo')->defaultValue(null)->end()
                                ->variableNode('branding')->defaultValue(null)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('voucher')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Voucher::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(VoucherRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(VoucherFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(VoucherType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addProductSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('product')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('variant_proxy')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('model')->defaultValue(ProductVariantProxy::class)->end()
                            ->scalarNode('factory')->defaultValue(ProductVariantProxyFactory::class)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

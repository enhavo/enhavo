<?php

namespace Enhavo\Bundle\TemplateBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Factory\TemplateFactory;
use Enhavo\Bundle\TemplateBundle\Form\Type\TemplateType;
use Enhavo\Bundle\TemplateBundle\Repository\TemplateRepository;
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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_template');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            // Driver used by the resource bundle
            ->children()
               ->scalarNode('driver')->defaultValue('doctrine/orm')->end()
            ->end()

            ->children()
                ->arrayNode('template')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('repository')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translation_domain')->end()
                            ->scalarNode('template')->defaultValue('theme/resource/template/show.html.twig')->end()
                            ->scalarNode('resource_template')->end()
                        ->end()
                    ->end()
                 ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

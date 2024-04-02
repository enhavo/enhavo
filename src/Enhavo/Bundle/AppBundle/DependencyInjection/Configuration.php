<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\EnhavoAppBundle;
use Enhavo\Bundle\AppBundle\Locale\FixLocaleResolver;
use Enhavo\Bundle\AppBundle\Mailer\Message;
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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_app');
        $rootNode = $treeBuilder->getRootNode();

        $this->addViewerSectionSection($rootNode);
        $this->addVueSection($rootNode);
        $this->addViteSection($rootNode);
        $this->addAreaSection($rootNode);
        $this->addEndpointSection($rootNode);
        $this->addMailSectionSection($rootNode);
        $this->addWebpackBuildSection($rootNode);
        $this->addFormThemesSection($rootNode);
        $this->addTemplatePathsSection($rootNode);
        $this->addLoginSection($rootNode);
        $this->addToolbarWidgetSection($rootNode);
        $this->addBrandingSection($rootNode);
        $this->addMenuSection($rootNode);
        $this->addLocaleSection($rootNode);
        $this->addRolesSection($rootNode);

        return $treeBuilder;
    }

    private function addViewerSectionSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('stylesheets')
                    ->prototype('scalar')->end()
                ->end()
            ->end()

            ->children()
                ->arrayNode('javascripts')
                    ->prototype('scalar')->end()
                ->end()
            ->end()

            ->children()
                ->arrayNode('apps')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }

    private function addVueSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('vue')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('route_providers')
                            ->variablePrototype()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addEndpointSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('endpoint')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template_url_prefix')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addAreaSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('area')
                    ->prototype('array')
                        ->children()
                            ->variableNode('firewall')
                                ->defaultValue(null)
                            ->end()
                            ->variableNode('path')
                                ->defaultValue(null)
                            ->end()
                            ->arrayNode('options')
                                ->variablePrototype()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addMailSectionSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('mailer')
                    ->children()
                        ->scalarNode('model')->defaultValue(Message::class)->end()
                        ->arrayNode('defaults')
                            ->children()
                                ->scalarNode('from')->defaultValue(null)->end()
                                ->scalarNode('name')->defaultValue(null)->end()
                                ->scalarNode('to')->defaultValue(null)->end()
                            ->end()
                        ->end()

                        ->arrayNode('mails')
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('from')->defaultValue(null)->end()
                                    ->scalarNode('name')->defaultValue(null)->end()
                                    ->scalarNode('to')->defaultValue(null)->end()
                                    ->variableNode('cc')->defaultValue(null)->end()
                                    ->variableNode('bcc')->defaultValue(null)->end()
                                    ->scalarNode('subject')->end()
                                    ->scalarNode('translation_domain')->defaultValue(null)->end()
                                    ->scalarNode('template')->end()
                                    ->scalarNode('content_type')->defaultValue('text/plain')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addWebpackBuildSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('webpack_build')->defaultValue('_default')->end()
            ->end()
        ;
    }

    private function addFormThemesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('form_themes')
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    private function addTemplatePathsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('template_paths')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')->isRequired()->end()
                            ->scalarNode('alias')->isRequired()->end()
                            ->scalarNode('priority')->defaultValue(150)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addLoginSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('login')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('route')->defaultValue('enhavo_dashboard_index')->end()
                        ->scalarNode('route_parameters')->defaultValue([])->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addToolbarWidgetSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('toolbar_widget')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('primary')
                            ->useAttributeAsKey('name')
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('secondary')
                            ->useAttributeAsKey('name')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addBrandingSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('branding')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable')->defaultValue(true)->end()
                        ->scalarNode('enable_version')->defaultValue(true)->end()
                        ->scalarNode('enable_created_by')->defaultValue(true)->end()
                        ->scalarNode('logo')->defaultValue(null)->end()
                        ->scalarNode('text')->defaultValue('enhavo is an open source content-management-system based on symfony and sylius.')->end()
                        ->scalarNode('version')->defaultValue(EnhavoAppBundle::VERSION)->end()
                        ->scalarNode('background_image')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addMenuSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->variableNode('menu')->end()
            ->end();
    }

    private function addLocaleSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('locale')->defaultValue('en')->end()
                ->scalarNode('locale_resolver')->defaultValue(FixLocaleResolver::class)->end()
            ->end();
    }

    private function addRolesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('roles')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('role')->end()
                            ->scalarNode('label')->defaultValue('')->end()
                            ->scalarNode('translation_domain')->defaultValue(null)->end()
                            ->scalarNode('display')->defaultValue(true)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addViteSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('vite')
                    ->children()
                        ->scalarNode('mode')->defaultValue('test')->end()
                        ->arrayNode('builds')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('host')->defaultValue('localhost')->end()
                                    ->scalarNode('port')->defaultValue('5200')->end()
                                    ->scalarNode('manifest')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}

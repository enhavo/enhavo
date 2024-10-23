<?php

namespace Enhavo\Bundle\CommentBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class EnhavoCommentExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('enhavo_comment.submit_form.form', $config['submit_form']['form']);
        $container->setParameter('enhavo_comment.submit_form.validation_groups', $config['submit_form']['validation_groups']);
        $container->setParameter('enhavo_comment.publish_strategy.strategy', $config['publish_strategy']['strategy']);
        $container->setParameter('enhavo_comment.publish_strategy.options', $config['publish_strategy']['options']);

        $configFiles = array(
            'services.yaml',
        );

        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/comment.yaml',
            __DIR__.'/../Resources/config/resources/thread.yaml',
        ];
    }
}

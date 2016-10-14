<?php

namespace Enhavo\Bundle\ContactBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoContactExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if(isset($config['forms']) && is_array($config['forms'])) {
            foreach($config['forms'] as $name => $form) {
                $container->setParameter(sprintf('enhavo_contact.%s.model', $name), $form['model']);
                $container->setParameter(sprintf('enhavo_contact.%s.form', $name), $form['form']);
                $container->setParameter(sprintf('enhavo_contact.%s.template.form', $name), $form['template']['form']);
                $container->setParameter(sprintf('enhavo_contact.%s.template.recipient', $name), $form['template']['recipient']);
                $container->setParameter(sprintf('enhavo_contact.%s.template.confirm', $name), $form['template']['confirm']);
                $container->setParameter(sprintf('enhavo_contact.%s.recipient', $name), $form['recipient']);
                $container->setParameter(sprintf('enhavo_contact.%s.from', $name), $form['from']);
                $container->setParameter(sprintf('enhavo_contact.%s.subject', $name), $form['subject']);
                $container->setParameter(sprintf('enhavo_contact.%s.translation_domain', $name), $form['translation_domain']);
                $container->setParameter(sprintf('enhavo_contact.%s.confirm_mail', $name), $form['confirm_mail']);
            }
            $container->setParameter('enhavo_contact.forms', $config['forms']);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}

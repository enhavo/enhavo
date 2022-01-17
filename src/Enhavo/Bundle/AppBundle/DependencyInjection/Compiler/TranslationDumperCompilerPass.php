<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;
use Symfony\Component\DependencyInjection\Reference;

class TranslationDumperCompilerPass implements CompilerPassInterface
{
    const SERVICE = 'enhavo_app.translation.translation_dumper';

    public function process(ContainerBuilder $container)
    {
        // ToDo: Find translation files
        //$this->addLoaders($container);
        //$this->replaceArgument($container);
    }

    private function addLoaders(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('translation.loader') as $loaderId => $attributes) {
            $attributes = array_shift($attributes);

            $container
                ->getDefinition(self::SERVICE)
                ->addMethodCall('addLoader', array($attributes['alias'], new Reference($loaderId)));
        }
    }

    private function replaceArgument(ContainerBuilder $container)
    {
        $translationFiles = $this->getTranslationFilesFromAddResourceCalls($container);
        $translationFiles = array_merge($translationFiles, $this->getTranslationFiles($container));

        $container->getDefinition(self::SERVICE)->replaceArgument(0, $translationFiles);
    }

    private function getTranslationFilesFromAddResourceCalls(ContainerBuilder $container)
    {
        $translationFiles = array();

        $methodCalls = $container->findDefinition('translator.default')->getMethodCalls();
        foreach ($methodCalls as $methodCall) {
            if ($methodCall[0] === 'addResource') {
                $locale = $methodCall[1][2];
                $filename = $methodCall[1][1];

                if (!isset($translationFiles[$locale])) {
                    $translationFiles[$locale] = array();
                }

                $translationFiles[$locale][] = $filename;
            }
        }

        return $translationFiles;
    }

    private function getTranslationFiles(ContainerBuilder $container)
    {
        $translationFiles = array();
        $translator = $container->findDefinition('translator.default');

        try {
            $translatorOptions = $translator->getArgument(4);
        } catch (OutOfBoundsException $e) {
            $translatorOptions = array();
        }

        $translatorOptions = array_merge($translatorOptions, $translator->getArgument(3));

        if (isset($translatorOptions['resource_files'])) {
            $translationFiles = $translatorOptions['resource_files'];
        }

        return $translationFiles;
    }
}

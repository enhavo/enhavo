<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;
use Symfony\Component\DependencyInjection\Reference;

class TranslationDumperCompilerPass implements CompilerPassInterface
{
    public const SERVICE = 'enhavo_app.translation.translation_dumper';

    public function process(ContainerBuilder $container)
    {
        $this->addLoaders($container);
        $this->replaceArgument($container);
    }

    private function addLoaders(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('translation.loader') as $loaderId => $attributes) {
            $attributes = array_shift($attributes);

            $container
                ->getDefinition(self::SERVICE)
                ->addMethodCall('addLoader', [$attributes['alias'], new Reference($loaderId)]);
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
        $translationFiles = [];

        $methodCalls = $container->findDefinition('translator.default')->getMethodCalls();
        foreach ($methodCalls as $methodCall) {
            if ('addResource' === $methodCall[0]) {
                $locale = $methodCall[1][2];
                $filename = $methodCall[1][1];

                if (!isset($translationFiles[$locale])) {
                    $translationFiles[$locale] = [];
                }

                $translationFiles[$locale][] = $filename;
            }
        }

        return $translationFiles;
    }

    private function getTranslationFiles(ContainerBuilder $container)
    {
        $translationFiles = [];
        $translator = $container->findDefinition('translator.default');

        try {
            $translatorOptions = $translator->getArgument(4);
        } catch (OutOfBoundsException $e) {
            $translatorOptions = [];
        }

        if (isset($translatorOptions['resource_files'])) {
            $translationFiles = $translatorOptions['resource_files'];
        }

        return $translationFiles;
    }
}

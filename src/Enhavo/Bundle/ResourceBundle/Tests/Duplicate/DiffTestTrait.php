<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Duplicate;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\DiffValueFormatter;
use Enhavo\Bundle\ResourceBundle\Duplicate\DiffFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\Duplicate;
use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\BaseDuplicateType;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\Factory;
use Enhavo\Component\Type\Registry;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Builds a DiffFactory with the real duplicate type registry, only metadata, translator and entity manager are mocked.
 */
trait DiffTestTrait
{
    /**
     * @param TypeInterface[]                           $types      duplicate types besides the base type, parents first
     * @param array<class-string, array<string, array>> $properties duplicate configs per class and property
     */
    private function createDiffFactory(array $types, array $properties): DiffFactory
    {
        $container = new Container();
        $registry = new Registry('Duplicate');
        $registry->setContainer($container);
        foreach (array_merge([new BaseDuplicateType()], $types) as $type) {
            $container->set($type::class, $type);
            $registry->register($type::class, $type::class);
        }

        $metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $metadataRepository->method('getMetadata')->willReturnCallback(function ($class) use ($properties) {
            $metadata = new Metadata($class);
            $metadata->setProperties($properties[$class] ?? []);

            return $metadata;
        });

        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $translator->method('trans')->willReturnArgument(0);

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        return new DiffFactory(
            $metadataRepository,
            new Factory(Duplicate::class, $registry),
            new DiffValueFormatter($translator),
            $entityManager,
        );
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\FileNotFound;

use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Exception\FileNotFoundException;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;
use Psr\Container\ContainerInterface;

/**
 * Chain multiple FileNotFoundHandler.
 *
 * If one handler throw a FileException, StorageException or FileNotFoundException,
 * then the next handler will be used.
 *
 * Define handlers over parameters like:
 *
 * [
 *   "handlers" => [
 *      'MyFileNotFoundService',
 *      'MyOtherFileNotFoundServiceWithParameters' => ['param1' => 'value1'],
 *   ]
 * ]
 */
class ChainFileNotFoundHandler implements FileNotFoundHandlerInterface
{
    private ContainerInterface $container;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function handleSave(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        foreach ($this->getHandlers($parameters) as $handlerData) {
            try {
                $this->container->get($handlerData['handler'])->handleSave($file, $storage, $exception, $handlerData['parameters']);
                return;
            } catch (FileException|StorageException|FileNotFoundException $e) {
                // next handler
            }
        }

        throw new FileException('No FileNotFound handler can handle save');
    }

    public function handleLoad(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        foreach ($this->getHandlers($parameters) as $handlerData) {
            try {
                $this->container->get($handlerData['handler'])->handleLoad($file, $storage, $exception, $handlerData['parameters']);
                return;
            } catch (FileException|StorageException|FileNotFoundException $e) {
                // next handler
            }
        }

        throw new FileException('No FileNotFound handler can handle load');
    }

    public function handleDelete(FormatInterface|FileInterface $file, StorageInterface $storage, FileNotFoundException $exception, array $parameters = []): void
    {
        foreach ($this->getHandlers($parameters) as $handlerData) {
            try {
                $this->container->get($handlerData['handler'])->handleDelete($file, $storage, $exception, $handlerData['parameters']);
                return;
            } catch (FileException|StorageException|FileNotFoundException $e) {
                // next handler
            }
        }

        throw new FileException('No FileNotFound handler can handle delete');
    }

    public function handleFileNotFound(FileInterface|FormatInterface $file, array $parameters = []): void
    {
        foreach ($this->getHandlers($parameters) as $handlerData) {
            try {
                $this->container->get($handlerData['handler'])->handleFileNotFound($file, $handlerData['parameters']);
                return;
            } catch (FileException|StorageException|FileNotFoundException $e) {
                // next handler
            }
        }

        throw new FileException('No FileNotFound handler can handle file not found');
    }

    /**
     * @return array of an array with key handler and parameters e.g.:
     *
     * [
     *   ["handler" => "service", "parameters" => []],
     *   ["handler" => "service2", "parameters" => []],
     * ]
     */
    private function getHandlers(array $parameters): array
    {
        $handlers = [];
        if (isset($parameters['handlers'])) {
            foreach ($parameters['handlers'] as $key => $value) {
                if (is_int($key)) {
                    $handlers[] = [
                        'handler' => $value,
                        'parameters' => [],
                    ];
                } else {
                    $handlers[] = [
                        'handler' => $key,
                        'parameters' => $value,
                    ];
                }
            }
        }

        return $handlers;
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Profiler\EndpointDataCollector;
use Enhavo\Component\Type\AbstractContainerType;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property EndpointTypeInterface   $type
 * @property EndpointTypeInterface[] $parents
 */
class Endpoint extends AbstractContainerType
{
    public function __construct(
        TypeInterface $type,
        array $parents,
        array $options,
        ?string $key = null,
        array $extensions = [],
        private readonly ?EndpointDataCollector $endpointDataCollector = null,
    ) {
        parent::__construct($type, $parents, $options, $key, $extensions);
    }

    public function getResponse(Request $request): Response
    {
        $data = new Data();
        $context = new Context($request);

        $this->endpointDataCollector?->setOptions($this->options);

        foreach ($this->parents as $parent) {
            $parent->handleRequest($this->options, $request, $data, $context);
            $this->endpointDataCollector?->addParent($parent);
            if ($context->isStopped()) {
                return $context->getResponse() ?? $this->type->getResponse($this->options, $request, $data, $context);
            }
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->handleRequest($this->options, $request, $data, $context);
                    $this->endpointDataCollector?->addExtension($extension);
                    if ($context->isStopped()) {
                        return $context->getResponse() ?? $this->type->getResponse($this->options, $request, $data, $context);
                    }
                }
            }
        }

        $this->type->handleRequest($this->options, $request, $data, $context);
        $this->endpointDataCollector?->addType($this->type);

        if ($context->isStopped()) {
            $this->endpointDataCollector?->setContext($context);
            $this->endpointDataCollector?->setData($data);

            return $context->getResponse() ?? $this->type->getResponse($this->options, $request, $data, $context);
        }

        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->handleRequest($this->options, $request, $data, $context);
                $this->endpointDataCollector?->addExtension($extension);
                if ($context->isStopped()) {
                    $this->endpointDataCollector?->setContext($context);
                    $this->endpointDataCollector?->setData($data);

                    return $context->getResponse() ?? $this->type->getResponse($this->options, $request, $data, $context);
                }
            }
        }

        $this->endpointDataCollector?->setContext($context);
        $this->endpointDataCollector?->setData($data);

        if ($context->getResponse()) {
            return $context->getResponse();
        }

        return $this->type->getResponse($this->options, $request, $data, $context);
    }

    public function describe(Path $path)
    {
        foreach ($this->parents as $parent) {
            $parent->describe($this->options, $path);
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->describe($this->options, $path);
                }
            }
        }

        $this->type->describe($this->options, $path);
        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->describe($this->options, $path);
            }
        }
    }
}

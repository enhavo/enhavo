<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Profiler\EndpointDataCollector;
use Enhavo\Component\Type\AbstractContainerType;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Endpoint extends AbstractContainerType
{
    /** @var EndpointTypeInterface */
    protected $type;

    /** @var EndpointTypeInterface[] */
    protected $parents;

    public function __construct(
        TypeInterface $type,
        array $parents,
        array $options,
        string $key = null,
        array $extensions = [],
        private readonly EndpointDataCollector $endpointDataCollector,
    )
    {
        parent::__construct($type, $parents, $options, $key, $extensions);
    }

    public function getResponse(Request $request): Response
    {
        $data = new Data();
        $context = new Context();

        $this->endpointDataCollector->setOptions($this->options);

        foreach ($this->parents as $parent) {
            $parent->handleRequest($this->options, $request, $data, $context);
            $this->endpointDataCollector->addParent($parent);
            if ($context->isStopped()) {
                return $context->getResponse();
            }
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->handleRequest($this->options, $request, $data, $context);
                    $this->endpointDataCollector->addExtension($extension);
                    if ($context->isStopped()) {
                        return $context->getResponse();
                    }
                }
            }
        }

        $this->type->handleRequest($this->options, $request, $data, $context);
        $this->endpointDataCollector->addType($this->type);
        $this->endpointDataCollector->setData($data);
        if ($context->isStopped()) {
            return $context->getResponse();
        }

        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->handleRequest($this->options, $request, $data, $context);
                $this->endpointDataCollector->addExtension($extension);
                if ($context->isStopped()) {
                    return $context->getResponse();
                }
            }
        }

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

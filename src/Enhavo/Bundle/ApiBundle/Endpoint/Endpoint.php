<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Component\Type\AbstractContainerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Endpoint extends AbstractContainerType
{
    /** @var EndpointTypeInterface */
    protected $type;

    /** @var EndpointTypeInterface[] */
    protected $parents;

    public function getResponse(Request $request): Response
    {
        $data = new Data();
        $context = new Context();

        foreach ($this->parents as $parent) {
            $parent->handleRequest($this->options, $request, $data, $context);
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->handleRequest($this->options, $request, $data, $context);
                }
            }
        }

        $this->type->handleRequest($this->options, $request, $data, $context);
        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->handleRequest($this->options, $request, $data, $context);
            }
        }

        return $this->type->getResponse($this->options, $request, $data, $context);
    }
}

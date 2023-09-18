<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

class Parameter
{
    public function __construct(
        private array &$parameter,
        private $parent,
    )
    {
    }

    public function in($in): self
    {
        $this->parameter['in'] = $in;
        return $this;
    }
    public function description(?string $description): self
    {
        $this->parameter['description'] = $description;
        return $this;
    }

    public function required(bool $value): self
    {
        $this->parameter['required'] = $value;
        return $this;
    }

    public function deprecated(bool $value): self
    {
        $this->parameter['deprecated'] = $value;
        return $this;
    }

    public function allowEmptyValue(bool $value): self
    {
        $this->parameter['allowEmptyValue'] = $value;
        return $this;
    }

    /** @return Path|Method */
    public function end(): mixed
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\ArrayType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\BooleanType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\IntegerType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\NumberType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\ObjectType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\StringType;

class Schema extends Node
{
    public function ref($ref): self
    {
        $this->reset();
        $this->data['$ref'] = $ref;

        return $this;
    }

    public function object(): ObjectType
    {
        if (isset($this->data['type']) && 'object' === $this->data['type']) {
            return new ObjectType($this->data, $this);
        }

        $this->reset();

        return new ObjectType($this->data, $this);
    }

    public function string(): StringType
    {
        $this->reset();

        return new StringType($this->data, $this);
    }

    public function integer(): IntegerType
    {
        $this->reset();

        return new IntegerType($this->data, $this);
    }

    public function number(): NumberType
    {
        $this->reset();

        return new NumberType($this->data, $this);
    }

    public function boolean(): BooleanType
    {
        $this->reset();

        return new BooleanType($this->data, $this);
    }

    public function array(): ArrayType
    {
        $this->reset();

        return new ArrayType($this->data, $this);
    }

    private function reset()
    {
        foreach ($this->data as $key => $value) {
            unset($this->data[$key]);
        }
    }
}

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

class Parameter extends Node
{
    public function in($in): self
    {
        $this->data['in'] = $in;

        return $this;
    }

    public function description(?string $description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function required(bool $value = true): self
    {
        $this->data['required'] = $value;

        return $this;
    }

    public function deprecated(bool $value): self
    {
        $this->data['deprecated'] = $value;

        return $this;
    }

    public function allowEmptyValue(bool $value): self
    {
        $this->data['allowEmptyValue'] = $value;

        return $this;
    }

    public function schema(): Schema
    {
        if (!array_key_exists('schema', $this->data)) {
            $this->data['schema'] = [];
        }

        return new Schema($this->data['schema'], $this);
    }
}

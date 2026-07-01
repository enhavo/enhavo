<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Documentation\Model\Type;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Node;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;

/**
 * @method ObjectType|Schema end()
 */
class ArrayType extends Node
{
    public function __construct(
        array &$data,
              $parent,
    ) {
        parent::__construct($data, $parent);
        $this->data['type'] = 'array';
    }

    public function items(): Schema
    {
        if (!array_key_exists('items', $this->data)) {
            $this->data['items'] = [];
        }

        return new Schema($this->data['items'], $this);
    }

    public function minItems(int $value): self
    {
        $this->data['minItems'] = $value;

        return $this;
    }

    public function maxItems(int $value): self
    {
        $this->data['maxItems'] = $value;

        return $this;
    }

    public function uniqueItems(bool $value = true): self
    {
        $this->data['uniqueItems'] = $value;

        return $this;
    }

    public function description(string $value): self
    {
        $this->data['description'] = $value;

        return $this;
    }

    public function nullable(bool $value = true): self
    {
        $this->data['nullable'] = $value;

        return $this;
    }

    public function example($value): self
    {
        $this->data['example'] = $value;

        return $this;
    }
}

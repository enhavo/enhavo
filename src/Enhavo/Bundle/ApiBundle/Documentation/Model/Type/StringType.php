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
class StringType extends Node
{
    public function __construct(
        array &$data,
              $parent,
    ) {
        parent::__construct($data, $parent);
        $this->data['type'] = 'string';
    }

    public function format(string $value): self
    {
        $this->data['format'] = $value;

        return $this;
    }

    public function enum(array $values): self
    {
        $this->data['enum'] = $values;

        return $this;
    }

    public function minLength(int $value): self
    {
        $this->data['minLength'] = $value;

        return $this;
    }

    public function maxLength(int $value): self
    {
        $this->data['maxLength'] = $value;

        return $this;
    }

    public function pattern(string $value): self
    {
        $this->data['pattern'] = $value;

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

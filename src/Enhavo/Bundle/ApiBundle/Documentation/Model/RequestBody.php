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


use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\ObjectType;

/**
 * @method Path end()
 */
class RequestBody extends Node
{
    public function description($description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->data['required'] = $required;

        return $this;
    }

    public function content($mimeType = 'application/json'): Content
    {
        if (!array_key_exists('content', $this->data)) {
            $this->data['content'] = [];
        }

        if (!array_key_exists($mimeType, $this->data['content'])) {
            $this->data['content'][$mimeType] = [];
        }

        return new Content($this->data['content'][$mimeType], $this);
    }
}

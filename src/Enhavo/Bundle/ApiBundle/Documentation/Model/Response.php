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

class Response
{
    public function __construct(
        private array &$response,
        private Method $parent,
    ) {
    }

    public function description($description): self
    {
        $this->method['description'] = $description;

        return $this;
    }

    public function content($mimeType = 'application/json'): Content
    {
        if (!array_key_exists('content', $this->response)) {
            $this->response['content'] = [];
        }

        if (!array_key_exists($mimeType, $this->response['content'])) {
            $this->response['content'][$mimeType] = [];
        }

        return new Content($this->response['content'][$mimeType], $this);
    }

    public function end(): Method
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}

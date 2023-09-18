<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

class Content
{
    public function __construct(
        private array &$content,
        private $parent,
    )
    {
    }

    public function schema(): Schema
    {
        if (!array_key_exists('schema', $this->content)) {
            $this->content['schema'] = [];
        }

        return new Schema($this->content['schema'], $this);
    }

    public function end()
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}

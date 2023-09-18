<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

use Symfony\Component\Routing\Route;

class Documentation
{
    private array $data = [];

    public function path($url): Path
    {
        if (!isset($this->data['paths'])) {
            $this->data['paths'] = [];
        }

        if (is_string($url)) {
            if (!isset($this->data['paths'][$url])) {
                $this->data['paths'][$url] = [];
            }
            $path = &$this->data['paths'][$url];
        } elseif ($url instanceof Route) {
            if (!isset($this->data['paths'][$url->getPath()])) {
                $this->data['paths'][$url->getPath()] = [];
            }
            $path = &$this->data['paths'][$url->getPath()];
        } else {
            throw new \Exception();
        }

        return new Path($path, $this);
    }

    public function version($version): self
    {
        $this->data['openapi'] = $version;
        return $this;
    }

    public function getOutput(): array
    {
        return $this->data;
    }

    public function getDocumentation(): Documentation
    {
        return $this;
    }
}

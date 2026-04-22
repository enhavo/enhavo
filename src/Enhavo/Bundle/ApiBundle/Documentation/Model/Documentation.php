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

    public function info(): Info
    {
        if (!array_key_exists('info', $this->data)) {
            $this->data['info'] = [];
        }

        return new Info($this->data['info'], $this);
    }

    public function server($url): Server
    {
        if (!isset($this->data['servers'])) {
            $this->data['servers'] = [];
        }

        foreach ($this->data['servers'] as $key => $server) {
            if ($server['url'] === $url) {
                return new Server($this->data['servers'][$key], $this);
            }
        }

        $server = ['url' => $url];
        $this->data['servers'][] = &$server;

        return new Server($server, $this);
    }

    public function tag($name): Tag
    {
        if (!isset($this->data['tags'])) {
            $this->data['tags'] = [];
        }

        foreach ($this->data['tags'] as $key => $tag) {
            if ($tag['name'] === $name) {
                return new Tag($this->data['tags'][$key], $this);
            }
        }

        $tag = ['name' => $name];
        $this->data['tags'][] = &$tag;

        return new Tag($tag, $this);
    }

    public function components(): Components
    {
        if (!array_key_exists('components', $this->data)) {
            $this->data['components'] = [];
        }

        return new Components($this->data['components'], $this);
    }

    public function security(array $requirement): self
    {
        if (!array_key_exists('security', $this->data)) {
            $this->data['security'] = [];
        }

        $this->data['security'][] = $requirement;

        return $this;
    }

    public function externalDocs(): ExternalDocs
    {
        if (!array_key_exists('externalDocs', $this->data)) {
            $this->data['externalDocs'] = [];
        }

        return new ExternalDocs($this->data['externalDocs'], $this);
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

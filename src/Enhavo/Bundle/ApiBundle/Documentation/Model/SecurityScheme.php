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

class SecurityScheme extends Node
{
    public function type($type): self
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function description($description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function name($name): self
    {
        $this->data['name'] = $name;

        return $this;
    }

    public function in($in): self
    {
        $this->data['in'] = $in;

        return $this;
    }

    public function scheme($scheme): self
    {
        $this->data['scheme'] = $scheme;

        return $this;
    }

    public function bearerFormat($format): self
    {
        $this->data['bearerFormat'] = $format;

        return $this;
    }

    public function openIdConnectUrl($url): self
    {
        $this->data['openIdConnectUrl'] = $url;

        return $this;
    }
}

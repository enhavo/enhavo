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

class Info extends Node
{
    public function title($value): self
    {
        $this->data['title'] = $value;

        return $this;
    }

    public function description($value): self
    {
        $this->data['description'] = $value;

        return $this;
    }

    public function version($value): self
    {
        $this->data['version'] = $value;

        return $this;
    }

    public function termsOfService($value): self
    {
        $this->data['termsOfService'] = $value;

        return $this;
    }

    public function contact(): Contact
    {
        if (!array_key_exists('contact', $this->data)) {
            $this->data['contact'] = [];
        }

        return new Contact($this->data['contact'], $this);
    }

    public function license(): License
    {
        if (!array_key_exists('license', $this->data)) {
            $this->data['license'] = [];
        }

        return new License($this->data['license'], $this);
    }
}

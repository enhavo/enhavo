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

class License extends Node
{
    public function name($value): self
    {
        $this->data['name'] = $value;

        return $this;
    }

    public function url($value): self
    {
        $this->data['url'] = $value;

        return $this;
    }
}

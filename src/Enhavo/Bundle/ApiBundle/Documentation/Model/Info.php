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
}

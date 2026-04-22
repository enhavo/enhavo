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

class ExternalDocs extends Node
{
    public function description($description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function url($url): self
    {
        $this->data['url'] = $url;

        return $this;
    }
}

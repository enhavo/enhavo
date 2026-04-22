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

class Tag extends Node
{
    public function description($description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function externalDocs(): ExternalDocs
    {
        if (!array_key_exists('externalDocs', $this->data)) {
            $this->data['externalDocs'] = [];
        }

        return new ExternalDocs($this->data['externalDocs'], $this);
    }
}

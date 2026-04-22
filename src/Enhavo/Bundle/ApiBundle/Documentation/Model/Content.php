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

class Content extends Node
{
    public function schema(): Schema
    {
        if (!array_key_exists('schema', $this->data)) {
            $this->data['schema'] = [];
        }

        return new Schema($this->data['schema'], $this);
    }
}

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

class Info
{
    public function __construct(
        private array &$info,
        private Documentation $parent,
    ) {
    }

    public function title($value): self
    {
        $this->info['title'] = $value;

        return $this;
    }

    public function description($value): self
    {
        $this->info['description'] = $value;

        return $this;
    }

    public function version($value): self
    {
        $this->info['version'] = $value;

        return $this;
    }

    public function end(): Documentation
    {
        return $this->parent;
    }

    public function getDocumentation(): Info
    {
        return $this;
    }
}

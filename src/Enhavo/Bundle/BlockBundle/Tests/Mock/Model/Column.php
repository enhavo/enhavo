<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Mock\Model;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\BlockBundle\Entity\Node;

class Column extends AbstractBlock
{
    /** @var string|null */
    private $text;

    /** @var Node|null */
    private $column;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getColumn(): ?Node
    {
        return $this->column;
    }

    public function setColumn(?Node $column): void
    {
        $this->column = $column;
    }
}

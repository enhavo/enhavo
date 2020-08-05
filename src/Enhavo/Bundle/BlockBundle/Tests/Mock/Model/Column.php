<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-24
 * Time: 21:50
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

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Node|null
     */
    public function getColumn(): ?Node
    {
        return $this->column;
    }

    /**
     * @param Node|null $column
     */
    public function setColumn(?Node $column): void
    {
        $this->column = $column;
    }


}

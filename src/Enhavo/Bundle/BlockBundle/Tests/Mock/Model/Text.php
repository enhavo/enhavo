<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-24
 * Time: 21:51
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Mock\Model;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class Text extends AbstractBlock
{
    /** @var string|null */
    private $text;

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
}

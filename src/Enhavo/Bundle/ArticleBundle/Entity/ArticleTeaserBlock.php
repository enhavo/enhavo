<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Enhavo\Bundle\ArticleBundle\Model\ArticleInterface;
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class ArticleTeaserBlock extends AbstractBlock
{
    public const LAYOUT_1_1 = 0;
    public const LAYOUT_1_2 = 1;
    public const LAYOUT_2_1 = 2;

    /**
     * @var ArticleInterface
     */
    private $article;

    /**
     * @var int|null
     */
    private $layout = self::LAYOUT_1_2;

    /**
     * @var bool|null
     */
    private $textLeft = false;

    public function getArticle(): ?ArticleInterface
    {
        return $this->article;
    }

    public function setArticle(?ArticleInterface $article): void
    {
        $this->article = $article;
    }

    public function getLayout(): ?int
    {
        return $this->layout;
    }

    public function setLayout(?int $layout): void
    {
        $this->layout = $layout;
    }

    public function getTextLeft(): ?bool
    {
        return $this->textLeft;
    }

    public function setTextLeft(?bool $textLeft): void
    {
        $this->textLeft = $textLeft;
    }
}

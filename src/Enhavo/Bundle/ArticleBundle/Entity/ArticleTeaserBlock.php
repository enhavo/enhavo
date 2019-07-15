<?php

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Enhavo\Bundle\ArticleBundle\Model\ArticleInterface;
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class ArticleTeaserBlock extends AbstractBlock
{
    const LAYOUT_1_1 = 0;
    const LAYOUT_1_2 = 1;
    const LAYOUT_2_1 = 2;

    /**
     * @var ArticleInterface
     */
    private $article;

    /**
     * @var integer|null
     */
    private $layout = self::LAYOUT_1_2;

    /**
     * @var boolean|null
     */
    private $textLeft = false;

    /**
     * @return ArticleInterface|null
     */
    public function getArticle(): ?ArticleInterface
    {
        return $this->article;
    }

    /**
     * @param ArticleInterface|null $article
     */
    public function setArticle(?ArticleInterface $article): void
    {
        $this->article = $article;
    }

    /**
     * @return int|null
     */
    public function getLayout(): ?int
    {
        return $this->layout;
    }

    /**
     * @param int|null $layout
     */
    public function setLayout(?int $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * @return bool|null
     */
    public function getTextLeft(): ?bool
    {
        return $this->textLeft;
    }

    /**
     * @param bool|null $textLeft
     */
    public function setTextLeft(?bool $textLeft): void
    {
        $this->textLeft = $textLeft;
    }
}

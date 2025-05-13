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

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class ArticleListBlock extends AbstractBlock
{
    /**
     * @var bool
     */
    private $pagination = true;

    /**
     * @var int
     */
    private $limit = 10;

    public function getPagination(): ?bool
    {
        return $this->pagination;
    }

    public function setPagination(?bool $pagination = null): void
    {
        $this->pagination = $pagination;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit = null): void
    {
        $this->limit = $limit;
    }
}

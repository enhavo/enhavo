<?php

namespace Enhavo\Bundle\ArticleBundle\Entity;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;

class ArticleStream extends AbstractItem
{
    /**
     * @var  boolean
     */
    private $pagination = true;

    /**
     * @var integer
     */
    private $limit = 10;

    /**
     * @return bool
     */
    public function getPagination(): ?bool
    {
        return $this->pagination;
    }

    /**
     * @param bool $pagination
     */
    public function setPagination(bool $pagination = null): void
    {
        $this->pagination = $pagination;
    }

    /**
     * @return int
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit = null): void
    {
        $this->limit = $limit;
    }
}

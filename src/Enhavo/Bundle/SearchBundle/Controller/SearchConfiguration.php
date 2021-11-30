<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

class SearchConfiguration
{
    /** @var ?string */
    private $template;

    /** @var ?int */
    private $maxPerPage = 10;

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return int|null
     */
    public function getMaxPerPage(): ?int
    {
        return $this->maxPerPage;
    }

    /**
     * @param int|null $maxPerPage
     */
    public function setMaxPerPage(?int $maxPerPage): void
    {
        $this->maxPerPage = $maxPerPage;
    }
}

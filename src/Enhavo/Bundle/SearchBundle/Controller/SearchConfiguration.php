<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

class SearchConfiguration
{
    private ?string $template;
    private ?int $maxPerPage = 10;
    private array $filters = [];

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    public function getMaxPerPage(): ?int
    {
        return $this->maxPerPage;
    }

    public function setMaxPerPage(?int $maxPerPage): void
    {
        $this->maxPerPage = $maxPerPage;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 09.05.18
 * Time: 15:56
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;


class Filter
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    private ?string $term = null;
    /** @var QueryInterface[] */
    private array $queries = [];
    private ?string $class = null;
    private ?string $orderBy = null;
    private ?string $orderDirection = null;
    private ?int $limit = null;
    private bool $fuzzy = false;

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(?string $term): self
    {
        $this->term = $term;
        return $this;
    }

    public function addQuery(string $key, QueryInterface $query): self
    {
        $this->queries[$key] = $query;
        return $this;
    }

    public function removeQuery(string $key): self
    {
        if(isset($this->queries[$key])) {
            unset($this->queries[$key]);
        }
        return $this;
    }

    /** @return QueryInterface[] */
    public function getQueries(): array
    {
        return $this->queries;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy, string $orderDirection = null): self
    {
        $this->orderDirection = $orderDirection;
        $this->orderBy = $orderBy;
        return $this;
    }

    public function getOrderDirection(): ?string
    {
        return $this->orderDirection;
    }

    public function setOrderDirection(?string $orderDirection): self
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function isFuzzy(): bool
    {
        return $this->fuzzy;
    }

    public function setFuzzy(bool $fuzzy): void
    {
        $this->fuzzy = $fuzzy;
    }
}

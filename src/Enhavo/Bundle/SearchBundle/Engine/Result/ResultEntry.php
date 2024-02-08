<?php

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

class ResultEntry
{
    public function __construct(
        private SubjectLoaderInterface $subject,
        private array $filterData,
        private ?int $score,
    )
    {
    }

    public function getSubject(): mixed
    {
        return $this->subject->getSubject();
    }

    public function getFilterData(): array
    {
        return $this->filterData;
    }

    public function getScore()
    {
        return $this->score;
    }
}

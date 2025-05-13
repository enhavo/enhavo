<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

class ResultEntry
{
    public function __construct(
        private SubjectLoaderInterface $subject,
        private array $filterData,
        private ?int $score,
    ) {
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

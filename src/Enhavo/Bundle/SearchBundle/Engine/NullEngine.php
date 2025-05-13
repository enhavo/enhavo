<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine;

use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;

class NullEngine implements SearchEngineInterface
{
    public function search(Filter $filter): ResultSummary
    {
        return new ResultSummary([], 0);
    }

    public function count(Filter $filter): int
    {
        return 0;
    }

    public function suggest(Filter $filter): array
    {
        return [];
    }

    public function searchPaginated(Filter $filter): ResultSummary
    {
        return new ResultSummary([], 0);
    }

    public function index($resource)
    {
    }

    public function removeIndex($resource)
    {
    }

    public function reindex(bool $force = false, ?string $class = null, ?OutputLoggerInterface $logger = null)
    {
    }

    public function initialize($force = false)
    {
    }

    public static function supports($dsn): bool
    {
        if (str_starts_with($dsn, 'null://')) {
            return true;
        }

        return false;
    }
}

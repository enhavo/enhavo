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
use Enhavo\Bundle\SearchBundle\Result\Result;
use Pagerfanta\Pagerfanta;

interface SearchEngineInterface
{
    /**
     * @return Result[]
     */
    public function search(Filter $filter): ResultSummary;

    public function count(Filter $filter): int;

    /** @return string[] */
    public function suggest(Filter $filter): array;

    /**
     * @return Pagerfanta
     */
    public function searchPaginated(Filter $filter): ResultSummary;

    /**
     * @return void
     */
    public function index($resource);

    /**
     * @return void
     */
    public function removeIndex($resource);

    /**
     * @return void
     */
    public function reindex(bool $force = false, ?string $class = null, ?OutputLoggerInterface $logger = null);

    /**
     * @return void
     */
    public function initialize($force = false);

    public static function supports($dsn): bool;
}

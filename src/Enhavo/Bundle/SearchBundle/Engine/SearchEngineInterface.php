<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 09.05.18
 * Time: 15:55
 */

namespace Enhavo\Bundle\SearchBundle\Engine;


use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;
use Enhavo\Bundle\SearchBundle\Result\Result;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;

interface SearchEngineInterface
{
    /**
     * @param Filter $filter
     * @return Result[]
     */
    public function search(Filter $filter): ResultSummary;

    /**
     * @param Filter $filter
     * @return int
     */
    public function count(Filter $filter): int;

    /** @return string[] */
    public function suggest(Filter $filter): array;

    /**
     * @param Filter $filter
     * @return Pagerfanta
     */
    public function searchPaginated(Filter $filter): ResultSummary;

    /**
     * @param $resource
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
    public function reindex(bool $force = false, string $class = null, OutputLoggerInterface $logger = null);

    /**
     * @return void
     */
    public function initialize($force = false);

    public static function supports($dsn): bool;
}

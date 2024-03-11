<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 09.05.18
 * Time: 15:55
 */

namespace Enhavo\Bundle\SearchBundle\Engine;


use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;

class NullEngine implements SearchEngineInterface
{
    public function search(Filter $filter): ResultSummary
    {
        return new ResultSummary([], 0);
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

    public function reindex()
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

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
use Enhavo\Bundle\SearchBundle\Result\Result;
use Pagerfanta\Pagerfanta;

interface SearchEngineInterface
{
    /**
     * @param Filter $filter
     * @return Result[]
     */
    public function search(Filter $filter): ResultSummary;

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
    public function reindex();

    /**
     * @return void
     */
    public function initialize($force = false);
}

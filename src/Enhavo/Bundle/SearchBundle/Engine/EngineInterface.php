<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 09.05.18
 * Time: 15:55
 */

namespace Enhavo\Bundle\SearchBundle\Engine;


use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Pagerfanta\Pagerfanta;

interface EngineInterface
{
    /**
     * @param Filter $filter
     * @return array
     */
    public function search(Filter $filter);

    /**
     * @param Filter $filter
     * @return Pagerfanta
     */
    public function searchPaginated(Filter $filter);

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
    public function initialize();
}
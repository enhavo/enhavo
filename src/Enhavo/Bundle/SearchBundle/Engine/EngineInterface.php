<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 09.05.18
 * Time: 15:55
 */

namespace Enhavo\Bundle\SearchBundle\Engine;


use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;

interface EngineInterface
{
    public function search(Filter $filter);

    public function searchPaginated(Filter $filter);

    public function index($resource);

    public function removeIndex($resource);

    public function reindex();
}
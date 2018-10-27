<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.10.18
 * Time: 15:14
 */

namespace Enhavo\Bundle\AppBundle\Filter;


class FilterQueryFactory
{
    /**
     * @return FilterQuery
     */
    public function create()
    {
        return new FilterQuery();
    }
}
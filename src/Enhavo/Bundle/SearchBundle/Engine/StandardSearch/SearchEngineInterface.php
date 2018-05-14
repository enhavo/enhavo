<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 18.05.16
 * Time: 15:10
 */

namespace Enhavo\Bundle\SearchBundle\Search;


interface SearchEngineInterface {
    public function search($query, $filters = [], $types = null, $fields = null);
}
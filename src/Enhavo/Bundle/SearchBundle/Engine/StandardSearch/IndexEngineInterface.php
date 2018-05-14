<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 18.05.16
 * Time: 15:21
 */

namespace Enhavo\Bundle\SearchBundle\Index;


interface IndexEngineInterface
{
    public function index($resource);

    public function removeIndex($resource);

    public function reindex();
}
<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-24
 * Time: 13:52
 */

namespace Enhavo\Bundle\BlockBundle\Model;

interface BlockInterface
{
    public function getNode();

    public function setNode(NodeInterface $node);
}

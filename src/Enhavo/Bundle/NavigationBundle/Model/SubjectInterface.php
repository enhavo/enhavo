<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-02
 * Time: 10:56
 */

namespace Enhavo\Bundle\NavigationBundle\Model;


interface SubjectInterface
{
    public function getNode(): ?NodeInterface;

    public function setNode(?NodeInterface $node);
}

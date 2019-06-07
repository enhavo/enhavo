<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.09.18
 * Time: 20:36
 */

namespace Enhavo\Bundle\BlockBundle\Model;


interface ContextAwareInterface
{
    public function setContext(Context $context);

    public function getContext();
}
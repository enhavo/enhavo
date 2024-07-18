<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;


interface GridMergeInterface
{
    public static function mergeOptions($before, $current): array;
}

<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge;


interface ConfigMergeInterface
{
    public static function mergeConfigs($before, $current): array;
}

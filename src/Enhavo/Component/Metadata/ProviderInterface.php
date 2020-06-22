<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 13:47
 */

namespace Enhavo\Component\Metadata;


interface ProviderInterface
{
    public function provide(Metadata $metadata, $normalizedData);
}

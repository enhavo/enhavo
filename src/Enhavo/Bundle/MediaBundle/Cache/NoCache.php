<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-08
 * Time: 21:27
 */

namespace Enhavo\Bundle\MediaBundle\Cache;


use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class NoCache implements CacheInterface
{
    public function invalid(FileInterface $file, $format)
    {

    }

    public function set(FileInterface $file, $format)
    {

    }

    public function refresh(FileInterface $file, $format)
    {

    }
}

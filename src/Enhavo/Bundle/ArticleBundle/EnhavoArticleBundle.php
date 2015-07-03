<?php

namespace Enhavo\Bundle\ArticleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoArticleBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle\Exception;

class VueFormException extends \Exception
{
    public static function missingVueData()
    {
        return new self(sprintf('Can\'t find vue_data in form vars. Make sure VueTypeExtension was loaded before!'));
    }
}

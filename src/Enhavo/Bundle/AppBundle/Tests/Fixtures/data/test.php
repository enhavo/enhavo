<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;

return function (Loader $loader) {
    return [
        'message' => 'Hello World!',
    ];
};

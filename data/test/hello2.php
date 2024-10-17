<?php

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;

return function(Loader $loader) {
    return [
        'message' => 'expr:lorem_ipsum(false, 1, 1, [3, 5], [4, 7], 0)',
    ];
};

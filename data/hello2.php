<?php

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;

return function(Loader $loader) {
    return [
        'message' => 'expr:lorem_ipsum()',
    ];
};

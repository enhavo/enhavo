<?php

use Enhavo\Bundle\AppBundle\Endpoint\Loader;

return function(Loader $loader) {
    return $loader->load('test.php');
};

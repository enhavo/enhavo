<?php

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;

return function(Loader $loader) {
    return $loader->load('test.php');
};

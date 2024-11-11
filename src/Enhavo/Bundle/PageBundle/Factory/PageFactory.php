<?php
namespace Enhavo\Bundle\PageBundle\Factory;

use Enhavo\Bundle\ContentBundle\Factory\ContentFactory;

class PageFactory extends ContentFactory
{
    public function __construct($className)
    {
        parent::__construct($className);
    }
}

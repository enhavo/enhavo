<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;

class ResourceController extends BaseController
{
    use ResourceControllerTrait;
    use TemplateResolverTrait;
}

<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 15:58
 */

namespace Enhavo\Bundle\AppBundle\Template;

interface TemplateResolverAwareInterface
{
    public function setTemplateResolver(TemplateResolver $templateResolver);
}

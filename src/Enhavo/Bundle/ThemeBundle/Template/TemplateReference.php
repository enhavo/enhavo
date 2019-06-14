<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-14
 * Time: 23:48
 */

namespace Enhavo\Bundle\ThemeBundle\Template;

use Symfony\Component\Templating\TemplateReference as SymfonyTemplateReference;

class TemplateReference extends SymfonyTemplateReference
{
    public function getPath()
    {
        return $this->parameters['path'];
    }

    public function setPath($path)
    {
        $this->parameters['path'] = $path;
    }
}

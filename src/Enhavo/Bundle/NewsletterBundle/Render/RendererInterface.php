<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-13
 * Time: 14:13
 */

namespace Enhavo\Bundle\NewsletterBundle\Render;


interface RendererInterface
{
    public function render($newsletter, array $parameters = []);
}

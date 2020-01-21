<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:28
 */

namespace Enhavo\Bundle\FormBundle\Form\Helper;


interface EntrypointFileManagerInterface
{
    public function getCssFiles($editorEntrypoint, $editorEntrypointBuild = null);
}

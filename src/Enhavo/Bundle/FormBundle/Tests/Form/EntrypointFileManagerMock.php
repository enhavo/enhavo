<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:25
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form;


use Enhavo\Bundle\FormBundle\Form\Helper\EntrypointFileManagerInterface;

class EntrypointFileManagerMock implements EntrypointFileManagerInterface
{
    public function getCssFiles($editorEntrypoint, $editorEntrypointBuild = null)
    {
        return [];
    }

}

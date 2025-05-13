<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Preview;

class PreviewManager
{
    private $previewFlag = false;

    public function isPreview()
    {
        return $this->previewFlag;
    }

    public function enablePreview()
    {
        $this->previewFlag = true;
    }

    public function disablePreview()
    {
        $this->previewFlag = false;
    }
}

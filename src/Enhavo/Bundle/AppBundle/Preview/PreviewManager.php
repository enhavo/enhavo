<?php

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

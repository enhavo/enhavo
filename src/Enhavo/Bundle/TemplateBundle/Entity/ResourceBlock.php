<?php

namespace Enhavo\Bundle\TemplateBundle\Entity;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class ResourceBlock extends AbstractBlock
{
    /**
     * @var \Enhavo\Bundle\TemplateBundle\Template\Template
     */
    private $template;

    /**
     * @return \Enhavo\Bundle\TemplateBundle\Template\Template
     */
    public function getTemplate(): \Enhavo\Bundle\TemplateBundle\Template\Template
    {
        return $this->template;
    }

    /**
     * @param \Enhavo\Bundle\TemplateBundle\Template\Template $template
     */
    public function setTemplate(\Enhavo\Bundle\TemplateBundle\Template\Template $template): void
    {
        $this->template = $template;
    }
}

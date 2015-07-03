<?php

namespace Enhavo\Bundle\AppBundle\Model;

class Tab
{
    /**
     * @var string
     */
    private $template = '';

    /**
     * @var string
     */
    private $label = '';

    /**
     * @param string $template
     * @return Tab
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $label
     * @return Tab
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
} 
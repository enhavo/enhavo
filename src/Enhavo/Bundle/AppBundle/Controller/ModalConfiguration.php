<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.08.19
 */

namespace Enhavo\Bundle\AppBundle\Controller;

class ModalConfiguration
{
    /**
     * @var string|null
     */
    private $form;

    /**
     * @var array
     */
    private $options  = [];

    /**
     * @var string|null
     */
    private $template;

    /**
     * @return string|null
     */
    public function getForm(): ?string
    {
        return $this->form;
    }

    /**
     * @param string|null $form
     */
    public function setForm(?string $form): void
    {
        $this->form = $form;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }
}

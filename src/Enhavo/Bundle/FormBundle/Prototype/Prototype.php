<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-24
 * Time: 08:41
 */

namespace Enhavo\Bundle\FormBundle\Prototype;


use Symfony\Component\Form\FormInterface;

class Prototype
{
    /** @var string */
    private $storageName;

    /** @var string */
    private $name;

    /** @var FormInterface */
    private $form;

    /** @var array */
    private $parameters;

    /**
     * Prototype constructor.
     * @param string $storageName
     * @param string $name
     * @param FormInterface $form
     * @param array $parameters
     */
    public function __construct(string $storageName, string $name, FormInterface $form, array $parameters)
    {
        $this->storageName = $storageName;
        $this->name = $name;
        $this->form = $form;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return $this->storageName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}

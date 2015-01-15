<?php
/**
 * Configuration.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Entity;


class Configuration
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return serialize($this);
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function getForm()
    {

    }

    public function setForm($form)
    {

    }
}
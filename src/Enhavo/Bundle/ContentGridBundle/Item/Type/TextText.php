<?php
/**
 * Text.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Item\Type;


class TextText
{
    /**
     * @var string
     */
    private $textLeft;

    private $titleLeft;

    private $textRight;

    private $titleRight;

    private $type;

    /**
     * @param string $text
     */
    public function setTextLeft($textLeft)
    {
        $this->textLeft = $textLeft;
    }

    /**
     * @return string
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $title
     */
    public function setTitleLeft($titleLeft)
    {
        $this->titleLeft = $titleLeft;
    }

    /**
     * @return mixed
     */
    public function getTitleLeft()
    {
        return $this->titleLeft;
    }

    /**
     * @return mixed
     */
    public function getTextRight()
    {
        return $this->textRight;
    }

    /**
     * @param mixed $textRight
     */
    public function setTextRight($textRight)
    {
        $this->textRight = $textRight;
    }

    /**
     * @return mixed
     */
    public function getTitleRight()
    {
        return $this->titleRight;
    }

    /**
     * @param mixed $titleRight
     */
    public function setTitleRight($titleRight)
    {
        $this->titleRight = $titleRight;
    }
}
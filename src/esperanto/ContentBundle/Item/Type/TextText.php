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
    private $text1;

    private $title1;

    private $text2;

    private $title2;

    private $type;

    /**
     * @param string $text
     */
    public function setText1($text1)
    {
        $this->text1 = $text1;
    }

    /**
     * @return string
     */
    public function getText1()
    {
        return $this->text1;
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
    public function setTitle1($title1)
    {
        $this->title1 = $title1;
    }

    /**
     * @return mixed
     */
    public function getTitle1()
    {
        return $this->title1;
    }

    /**
     * @return mixed
     */
    public function getText2()
    {
        return $this->text2;
    }

    /**
     * @param mixed $text2
     */
    public function setText2($text2)
    {
        $this->text2 = $text2;
    }

    /**
     * @return mixed
     */
    public function getTitle2()
    {
        return $this->title2;
    }

    /**
     * @param mixed $title2
     */
    public function setTitle2($title2)
    {
        $this->title2 = $title2;
    }
}
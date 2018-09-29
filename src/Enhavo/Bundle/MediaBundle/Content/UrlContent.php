<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:29
 */

namespace Enhavo\Bundle\MediaBundle\Content;


class UrlContent extends AbstractContent
{
    /**
     * @var
     */
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getContent()
    {
        return file_get_contents($this->url);
    }

    public function getFilePath()
    {
        return $this->url;
    }
}
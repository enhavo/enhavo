<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:26
 */

namespace Enhavo\Bundle\MediaBundle\Content;


interface ContentInterface
{
    public function getContent();

    public function getFilePath();

    public function equals(ContentInterface $content);
}
<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 28.09.18
 * Time: 19:00
 */

namespace Enhavo\Bundle\MediaBundle\Content;


abstract class AbstractContent implements ContentInterface
{
    public function equals(ContentInterface $content)
    {
        $fn1 = $content->getFilePath();
        $fn2 = $this->getFilePath();

        if(filetype($fn1) !== filetype($fn2))
            return false;

        if(filesize($fn1) !== filesize($fn2))
            return false;

        if(!$fp1 = fopen($fn1, 'rb'))
            return false;

        if(!$fp2 = fopen($fn2, 'rb')) {
            fclose($fp1);
            return false;
        }

        $same = true;
        while (!feof($fp1) and !feof($fp2))
            if(fread($fp1, 4096) !== fread($fp2, 4096)) {
                $same = false;
                break;
            }

        if(feof($fp1) !== feof($fp2))
            $same = false;

        fclose($fp1);
        fclose($fp2);

        return $same;
    }
}
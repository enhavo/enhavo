<?php
/**
 * ContentTypeResolver.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Service;

use esperanto\ContentBundle\Item\Type\Picture;
use esperanto\ContentBundle\Item\Type\Text;

class ContentTypeResolver
{
    public function resolve($type, $data)
    {
        switch($type) {
            case('text'):
                $itemType = new Text();
                if(array_key_exists('text', $data)) {
                    $itemType->setText($data['text']);
                }
                if(array_key_exists('title', $data)) {
                    $itemType->setTitle($data['title']);
                }
                break;
            case('picture'):
                $itemType = new Picture();
                if(array_key_exists('files', $data)) {
                    $itemType->setFiles($data['files']);
                }
                break;
            default:
                throw new \Exception('cant resolve type');
        }

        return $itemType;
    }
} 
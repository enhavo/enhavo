<?php
/**
 * ContentTypeResolver.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Service;

use esperanto\ContentBundle\Item\Type\Contact;
use esperanto\ContentBundle\Item\Type\CiteText;
use esperanto\ContentBundle\Item\Type\PicturePicture;
use esperanto\ContentBundle\Item\Type\TextText;
use esperanto\ContentBundle\Item\Type\TextPicture;
use esperanto\ContentBundle\Item\Type\Picture;
use esperanto\ContentBundle\Item\Type\Text;
use esperanto\ContentBundle\Item\Type\Video;

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
            case('textpicture'):
                $itemType = new TextPicture();
                if(array_key_exists('text', $data)) {
                    $itemType->setText($data['text']);
                }
                if(array_key_exists('title', $data)) {
                    $itemType->setTitle($data['title']);
                }
                if(array_key_exists('files', $data)) {
                    $itemType->setFiles($data['files']);
                }
                if(array_key_exists('textleft', $data)) {
                    $itemType->setTextleft($data['textleft']);
                }
                break;
            case('texttext'):
                $itemType = new TextText();
                if(array_key_exists('text1', $data)) {
                    $itemType->setText1($data['text1']);
                }
                if(array_key_exists('title1', $data)) {
                    $itemType->setTitle1($data['title1']);
                }
                if(array_key_exists('text2', $data)) {
                    $itemType->setText2($data['text2']);
                }
                if(array_key_exists('title2', $data)) {
                    $itemType->setTitle2($data['title2']);
                }
                break;
            case('picturepicture'):
                $itemType = new PicturePicture();
                if(array_key_exists('files1', $data)) {
                    $itemType->setFiles1($data['files1']);
                }
                if(array_key_exists('files2', $data)) {
                    $itemType->setFiles2($data['files2']);
                }
                break;
            case('citetext'):
                $itemType = new CiteText();
                if(array_key_exists('text', $data)) {
                    $itemType->setText($data['text']);
                }
                if(array_key_exists('title', $data)) {
                    $itemType->setTitle($data['title']);
                }
                if(array_key_exists('cite', $data)) {
                    $itemType->setCite($data['cite']);
                }
                if(array_key_exists('textLeft', $data)) {
                    $itemType->setTextLeft($data['textLeft']);
                }
                break;
            case('contact'):
                $itemType = new Contact();
                if(array_key_exists('files', $data)) {
                    $itemType->setFiles($data['files']);
                }
                if(array_key_exists('text', $data)) {
                    $itemType->setText($data['text']);
                }
                if(array_key_exists('title', $data)) {
                    $itemType->setTitle($data['title']);
                }
                break;
            case('video'):
                $itemType = new Video();
                if(array_key_exists('url', $data)) {
                    $itemType->setUrl($data['url']);
                }
                break;
            default:
                throw new \Exception('cant resolve type');
        }

        return $itemType;
    }
} 

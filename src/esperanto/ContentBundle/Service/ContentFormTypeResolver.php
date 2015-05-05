<?php
/**
 * ContentFormTypeResolver.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Service;

use esperanto\ContentBundle\Form\Type\ContactType;
use esperanto\ContentBundle\Form\Type\CiteTextType;
use esperanto\ContentBundle\Form\Type\PicturePictureType;
use esperanto\ContentBundle\Form\Type\TextTextType;

use esperanto\ContentBundle\Form\Type\PictureType;
use esperanto\ContentBundle\Form\Type\TextType;
use esperanto\ContentBundle\Form\Type\VideoType;

class ContentFormTypeResolver
{
    public function resolve($type, $formName = null, $data = null)
    {
        switch($type) {
            case('text'):
                $formType = new TextType($formName, $data);
                break;
            case('picture'):
                $formType = new PictureType($formName, $data);
                break;
            case('textpicture'):
                $formType = new TextPictureType($formName, $data);
                break;
            case('texttext'):
                $formType = new TextTextType($formName, $data);
                break;
            case('picturepicture'):
                $formType = new PicturePictureType($formName, $data);
                break;
            case('citetext'):
                $formType = new CiteTextType($formName, $data);
                break;
            case('contact'):
                $formType = new ContactType($formName, $data);
                break;
            case('video'):
                $formType = new VideoType($formName, $data);
                break;
            default:
                throw new \Exception('cant resolve form type');
        }

        return $formType;
    }
} 

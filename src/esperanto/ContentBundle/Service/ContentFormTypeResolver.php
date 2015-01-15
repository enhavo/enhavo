<?php
/**
 * ContentFormTypeResolver.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Service;


use esperanto\ContentBundle\Form\Type\PictureType;
use esperanto\ContentBundle\Form\Type\TextType;

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
            default:
                throw new \Exception('cant resolve form type');
        }

        return $formType;
    }
} 
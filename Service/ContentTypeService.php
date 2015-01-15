<?php
/**
 * ContentTypeService.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Service;


class ContentTypeService
{
    public function getResolver()
    {
        return new ContentTypeResolver();
    }

    public function getFormTypeResolver()
    {
        return new ContentFormTypeResolver();
    }
} 
<?php
/**
 * ItemTypeInterface.php
 *
 * @since 15/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DynamicForm;

interface ItemInterface
{
    public function getLabel();

    public function getType();

    public function getTranslationDomain();
}
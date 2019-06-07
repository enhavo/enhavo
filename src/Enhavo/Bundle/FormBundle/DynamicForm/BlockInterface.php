<?php
/**
 * BlockTypeInterface.php
 *
 * @since 15/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\DynamicForm;

interface BlockInterface
{
    public function getName();

    public function getLabel();

    public function getType();

    public function getTranslationDomain();
}
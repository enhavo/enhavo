<?php
/**
 * PropertyAccessor.php
 *
 * @since 24/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Component;

use Symfony\Component\PropertyAccess\PropertyAccessor as BasePropertyAccessor;

class PropertyAccessor extends BasePropertyAccessor
{
    public function __construct($magicCall = true, $throwExceptionOnInvalidIndex = false)
    {
        parent::__construct($magicCall, $throwExceptionOnInvalidIndex);
    }
} 
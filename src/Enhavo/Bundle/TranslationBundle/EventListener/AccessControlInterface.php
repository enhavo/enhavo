<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 14:45
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

interface AccessControlInterface
{
    public function isAccess(): bool;
}

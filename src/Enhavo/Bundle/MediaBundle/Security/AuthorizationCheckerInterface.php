<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 16:43
 */

namespace Enhavo\Bundle\MediaBundle\Security\AuthorizationChecker;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface AuthorizationCheckerInterface
{
    public function isGranted(FileInterface $file);
}
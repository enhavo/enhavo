<?php
/**
 * SymfonyAuthorizationChecker.php
 *
 * @since 18/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Security\AuthorizationChecker;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class DefaultAuthorizationChecker implements AuthorizationCheckerInterface
{
    public function isGranted(FileInterface $file)
    {
        return true;
    }
}
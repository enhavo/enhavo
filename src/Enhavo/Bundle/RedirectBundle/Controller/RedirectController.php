<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RedirectBundle\Controller;

use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends AbstractController
{
    public function redirectAction(RedirectInterface $contentDocument): Response
    {
        return new RedirectResponse($contentDocument->getTo(), $contentDocument->getCode());
    }
}

<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ExceptionListener
 *
 * In case of an AccessDeniedException, this class saves enhavo-view_id and post variables to the session, if any.
 *
 * This fixes the problem of a user getting logged out while editing a form in Enhavo backend. Now when the user gets
 * prompted with a login dialog when clicking Save, the dialog will not be hidden behind a permanent loading overlay
 * and on a successful login, the form's state as sent in the save request get restored.
 */
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            $request = $event->getRequest();
            if (preg_match('#/admin/#', $request->getRequestUri()) && $request->getSession()) {
                $request->getSession()->set('enhavo.view_id', $this->getViewId($request));
                $request->getSession()->set('enhavo.post', $this->getPostData($request));
                if (!$request->isXmlHttpRequest()) {
                    $request->getSession()->set('enhavo.redirect_uri', $request->getRequestUri());
                }
            }
        }
    }

    private function getViewId(Request $request)
    {
        if ($request->query->has('view_id')) {
            return $request->query->get('view_id');
        }

        return 0;
    }

    private function getPostData(Request $request)
    {
        $data = $request->request->all();
        if ($data) {
            return $data;
        }

        return null;
    }
}

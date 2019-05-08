<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-08
 * Time: 18:02
 */

namespace Enhavo\Bundle\AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExceptionListener extends \Symfony\Component\Security\Http\Firewall\ExceptionListener
{
    /**
     * @var Session
     */
    private $session;

    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof AccessDeniedException) {
            $request = $event->getRequest();
            if(preg_match('#/admin/#', $request->getRequestUri()) && $request->getSession()) {
                $request->getSession()->set('enhavo.view_id', $this->getViewId($request));
                $request->getSession()->set('enhavo.post', $this->getPostData($request));
                if(!$request->isXmlHttpRequest()) {
                    $request->getSession()->set('enhavo.redirect_uri', $request->getRequestUri());
                }
            }
        }

        parent::onKernelException($event);
    }

    private function getViewId(Request $request)
    {
        if($request->query->has('view_id')) {
            return $request->query->get('view_id');
        }
        return 0;
    }

    private function getPostData(Request $request)
    {
        $data = $request->request->all();
        if($data) {
            return $data;
        }
        return null;
    }
}

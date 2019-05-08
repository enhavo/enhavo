<?php
/**
 * LoginViewer.php
 *
 * @since 26/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\AbstractViewer;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginViewer extends AbstractViewer
{
    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Session
     */
    private $session;

    public function __construct(FlashBag $flashBag, Session $session)
    {
        $this->flashBag = $flashBag;
        $this->session = $session;
    }

    public function getType()
    {
        return 'login';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);

        $templateVars = $view->getTemplateData();
        $templateVars['data'] = [
            'messages' => $this->getFlashMessages(),
            'view' => [
                'id' => $this->getViewId()
            ]
        ];

        $templateVars = array_merge($templateVars, $options['parameters']);
        $templateVars['redirect_uri'] = $this->session->get('enhavo.redirect_uri');
        $view->setTemplateData($templateVars);

        return $view;
    }

    private function getViewId()
    {
        $viewId = $this->session->get('enhavo.view_id');
        if($viewId) {
            return $viewId;
        }
        return 0;
    }

    private function getFlashMessages()
    {
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach($types as $type) {
            foreach($this->flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => is_array($message) ? $message['message'] : $message,
                    'type' => $type
                ];
            }
        }
        return $messages;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'template' => 'EnhavoUserBundle:Admin/Security:login.html.twig',
            'stylesheets' => [
                'enhavo/login'
            ],
            'javascripts' => [
                'enhavo/login'
            ],
            'parameters' => [

            ]
        ]);
    }
}

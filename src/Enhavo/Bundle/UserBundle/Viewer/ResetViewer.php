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
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetViewer extends AbstractViewer
{
    /**
     * @var FlashBag
     */
    private $flashBag;

    public function __construct(FlashBag $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function getType()
    {
        return 'reset';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);

        $templateVars = $view->getTemplateData();
        $templateVars['form'] = $options['form'];
        $templateVars['data'] = [
            'messages' => $this->getFlashMessages(),
        ];

        $templateVars = array_merge($templateVars, $options['parameters']);

        $view->setTemplateData($templateVars);

        return $view;
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
            'template' => 'admin/resource/user/reset-password.html.twig',
            'stylesheets' => [
                'enhavo/login'
            ],
            'javascripts' => [
                'enhavo/login'
            ],
            'parameters' => [

            ],
            'form' => null,
        ]);
    }
}

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

class LoginViewer extends AbstractViewer
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

        ];

        $templateVars = array_merge($templateVars, $options['parameters']);

        $view->setTemplateData($templateVars);

        return $view;
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

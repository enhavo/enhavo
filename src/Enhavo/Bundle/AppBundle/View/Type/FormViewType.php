<?php
/**
 * FormViewer.php
 *
 * @since 22/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\Viewer\AbstractActionViewer;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormViewType extends AbstractViewType
{
    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var array
     */
    private $formThemes;

    /**
     * FormViewer constructor.
     * @param ActionManager $actionManager
     * @param FlashBag $flashBag
     * @param array $formThemes
     */
    public function __construct(ActionManager $actionManager, FlashBag $flashBag, array $formThemes)
    {
        parent::__construct($actionManager);
        $this->flashBag = $flashBag;
        $this->formThemes = $formThemes;
    }

    public function getType()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function createView($options = []): View
    {
        $view = parent::createView($options);
        $templateVars = $view->getTemplateData();

        if($options['tabs'] == null) {
            $tabs = [
                'main' => [
                    'label' => '',
                    'template' => $options['form_template']
                ],
            ];
        } else {
            $tabs = $options['tabs'];
        }

        $templateVars['tabs'] = $tabs;
        $templateVars['data']['tabs'] = $this->createTabViewData($tabs, $options['translation_domain']);

        $templateVars['data']['messages'] = $this->getFlashMessages();
        $templateVars['data']['modals'] = [];

        if($options['resource']) {
            $templateVars['data']['resource'] = [
                'id' => $options['resource']->getId()
            ];
        }

        $templateVars['form'] = $options['form']->createView();
        $templateVars['form_themes'] = $this->formThemes;

        $view->setTemplateData($templateVars);
        return $view;
    }

    protected function createActions($options)
    {
        $default = [
            'save' => [
                'type' => 'save',
            ],
        ];

        return $default;
    }

    private function createTabViewData($configuration, $translationDomain)
    {
        $data = [];
        foreach($configuration as $key => $tab) {
            $tabData = [];
            $tabData['label'] = $this->container->get('translator')->trans($tab['label'], [], isset($tab['translation_domain']) ? $tab['translation_domain'] : $translationDomain);
            $tabData['key'] = $key;
            $tabData['fullWidth'] = isset($tabData['full_width']) && $tabData['full_width'] ? true : false;
            $data[] = $tabData;
        }
        return $data;
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
            'javascripts' => [
                'enhavo/app/form'
            ],
            'stylesheets' => [
                'enhavo/app/form'
            ],
            'translations' => true,
            'routes' => false,
            'template' => 'admin/view/form.html.twig',
            'form_template' => 'admin/view/form-template.html.twig',
            'resource' => null,
            'translation_domain' => null,
            'tabs' => null
        ]);
        $optionsResolver->setRequired(['form']);
    }
}

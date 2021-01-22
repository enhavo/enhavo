<?php
/**
 * CreateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateViewer extends BaseViewer
{
    /**
     * @var string[]
     */
    private $formThemes;

    /**
     * @var ActionManager
     */
    protected $actionManager;

    /**
     * @var FlashBag
     */
    protected $flashBag;

    /**
     * CreateViewer constructor.
     * @param RequestConfigurationFactory $requestConfigurationFactory
     * @param ViewerUtil $util
     * @param array $formThemes
     * @param ActionManager $actionManager
     * @param FlashBag $flashBag
     */
    public function __construct(
        RequestConfigurationFactory $requestConfigurationFactory,
        ViewerUtil $util,
        array $formThemes,
        ActionManager $actionManager,
        FlashBag $flashBag
    )
    {
        parent::__construct($requestConfigurationFactory, $util);
        $this->formThemes = $formThemes;
        $this->actionManager = $actionManager;
        $this->flashBag = $flashBag;
    }

    public function getType()
    {
        return 'create';
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        /** @var Form $form */
        $form = $options['form'];

        $parameters->set('form', $form->createView());

        $label = $this->mergeConfig([
            $options['label'],
            $this->getViewerOption('label', $requestConfiguration)
        ]);

        $actions = $this->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]);

        $actionsSecondary = $this->mergeConfigArray([
            $options['actions_secondary'],
            $this->getViewerOption('actions_secondary', $requestConfiguration)
        ]);

        $tabs = $this->mergeConfigArray([
            [
                'main' => [
                    'label' => sprintf('%s.label.%s', $this->getUnderscoreName($metadata), $this->getUnderscoreName($metadata)),
                    'template' => 'admin/view/form_template.html.twig'
                ],
            ],
            $options['tabs'],
            $this->getViewerOption('tabs', $requestConfiguration)
        ]);
        $parameters->set('tabs', $tabs);

        $parameters->set('form_template', $this->mergeConfig([
            $options['form_template'],
            $this->getViewerOption('form.template', $requestConfiguration)
        ]));

        $parameters->set('form_action', $this->mergeConfig([
            sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            $options['form_action'],
            $this->getViewerOption('form.action', $requestConfiguration)
        ]));

        $parameters->set('form_action_parameters', $this->mergeConfigArray([
            $options['form_action_parameters'],
            $this->getViewerOption('form.action_parameters', $requestConfiguration)
        ]));

        $parameters->set('form_themes', $this->mergeConfigArray([
            $this->formThemes,
            $options['form_themes'],
            $this->getViewerOption('form.themes', $requestConfiguration)
        ]));

        $parameters->set('data', [
            'actions' => $this->actionManager->createActionsViewData($actions, $options['resource']),
            'actionsSecondary' => $this->actionManager->createActionsViewData($actionsSecondary, $options['resource']),
            'tabs' => $this->createTabViewData($tabs, $parameters->get('translation_domain')),
            'messages' => $this->getFlashMessages(),
            'modals' => [],
            'view' => [
                'id' => $this->getViewId(),
                'label' => $this->container->get('translator')->trans($label, [], $parameters->get('translation_domain'))
            ],
            'cssClass' => $this->getViewerOption('css_class', $requestConfiguration)
        ]);
    }

    protected function createTabViewData($configuration, $translationDomain)
    {
        $data = [];
        foreach($configuration as $key => $tab) {
            $tabData = [];
            $tabData['label'] = $this->container->get('translator')->trans($tab['label'], [], isset($tab['translation_domain']) ? $tab['translation_domain'] : $translationDomain);
            $tabData['key'] = $key;
            $tabData['fullWidth'] = isset($tab['full_width']) && $tab['full_width'] ? true : false;
            $data[] = $tabData;
        }
        return $data;
    }

    private function createActions($options)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $default = [
            'save' => [
                'type' => 'save',
                'route' => sprintf('%s_%s_create', $metadata->getApplicationName(), $this->getUnderscoreName($metadata)),
            ],
        ];

        return $default;
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
            'tabs' => [],
            'javascripts' => [
                'enhavo/app/form'
            ],
            'stylesheets' => [
                'enhavo/app/form'
            ],
            'actions' => [],
            'actions_secondary' => [],
            'form' => null,
            'form_themes' => [],
            'form_action' => null,
            'form_action_parameters' => [],
            'form_template' => 'EnhavoAppBundle:View:tab.html.twig',
            'template' => 'admin/view/form.html.twig',
            'translation_domain' => 'EnhavoAppBundle',
            'label' => 'label.create'
        ]);
    }
}

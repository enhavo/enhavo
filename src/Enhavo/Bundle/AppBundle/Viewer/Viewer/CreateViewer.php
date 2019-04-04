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
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
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
    private $actionManager;

    /**
     * CreateViewer constructor.
     * @param RequestConfigurationFactory $requestConfigurationFactory
     * @param ViewerUtil $util
     * @param array $formThemes
     * @param ActionManager $actionManager
     */
    public function __construct(
        RequestConfigurationFactory $requestConfigurationFactory,
        ViewerUtil $util,
        array $formThemes,
        ActionManager $actionManager
    )
    {
        parent::__construct($requestConfigurationFactory, $util);
        $this->formThemes = $formThemes;
        $this->actionManager = $actionManager;
    }

    public function getType()
    {
        return 'create';
    }

    /**
     * {@inheritdoc}
     */
    protected function create($options): View
    {
        /** @var FormInterface $form */
        $form = $options['form'];

        if($form->isSubmitted()) {
            $view = View::create($form, $form->isValid() ? 200 : 400);
            $view->setFormat('json');
            return $view;
        }

        return parent::create($options);
    }

    protected function buildTemplateParameters(ParameterBag $parameters, RequestConfiguration $requestConfiguration, array $options)
    {
        parent::buildTemplateParameters($parameters, $requestConfiguration, $options);

        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];
        /** @var Form $form */
        $form = $options['form'];

        $parameters->set('form', $form->createView());

        $actions = $this->mergeConfigArray([
            $this->createActions($options),
            $options['actions'],
            $this->getViewerOption('actions', $requestConfiguration)
        ]);

        $parameters->set('data', [
            'actions' => $this->actionManager->createActionsViewData($actions)
        ]);

        $tabs = $this->mergeConfigArray([
            [
                'main' => [
                    'label' => sprintf('%s.label.%s', $this->getUnderscoreName($metadata), $this->getUnderscoreName($metadata)),
                    'template' => 'EnhavoAppBundle:Tab:default.html.twig'
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
            'actions' => $this->actionManager->createActionsViewData($actions),
            'tabs' => $this->createTabViewData($tabs, $parameters->get('translationDomain'))
        ]);

        $parameters->set('resource', $options['resource']);
    }

    protected function createTabViewData($configuration, $translationDomain)
    {
        $data = [];
        foreach($configuration as $key => $tab) {
            $tabData = [];
            $tabData['label'] = $this->container->get('translator')->trans($tab['label'], [], $translationDomain);
            $tabData['key'] = $key;
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
            'close' => [
                'type' => 'close'
            ]
        ];

        return $default;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'tabs' => [],
            'javascripts' => [
                'enhavo/form'
            ],
            'stylesheets' => [
                'enhavo/form'
            ],
            'actions' => [],
            'form' => null,
            'form_themes' => [],
            'form_action' => null,
            'form_action_parameters' => [],
            'form_template' => 'EnhavoAppBundle:View:tab.html.twig',
            'template' => 'EnhavoAppBundle:Viewer:form.html.twig'
        ]);
    }
}

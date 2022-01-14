<?php
/**
 * CreateViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewUtil;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateViewType extends AbstractViewType
{
    /** @var string[] */
    private $formThemes;

    /** @var ActionManager */
    private $actionManager;

    /** @var FlashBag */
    private $flashBag;

    /** @var ViewUtil */
    private $util;

    /** @var RouterInterface */
    private $router;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * CreateViewType constructor.
     * @param string[] $formThemes
     * @param ActionManager $actionManager
     * @param FlashBag $flashBag
     * @param ViewUtil $util
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(array $formThemes, ActionManager $actionManager, FlashBag $flashBag, ViewUtil $util, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->formThemes = $formThemes;
        $this->actionManager = $actionManager;
        $this->flashBag = $flashBag;
        $this->util = $util;
        $this->router = $router;
        $this->translator = $translator;
    }

    public static function getName(): ?string
    {
        return 'create';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function createViewData($options, ViewData $data)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $configuration = $this->util->getRequestConfiguration($options);

        $actions = $this->util->mergeConfigArray([
            $this->createActions(),
            $options['actions'],
            $this->util->getViewerOption('actions', $configuration)
        ]);

        $actionsSecondary = $this->util->mergeConfigArray([
            $options['actions_secondary'],
            $this->util->getViewerOption('actions_secondary', $configuration)
        ]);

        $tabs = $this->util->mergeConfigArray([
            [
                'main' => [
                    'label' => sprintf('%s.label.%s', $this->util->getUnderscoreName($metadata), $this->util->getUnderscoreName($metadata)),
                    'template' => 'admin/view/form_template.html.twig'
                ],
            ],
            $options['tabs'],
            $this->util->getViewerOption('tabs', $configuration)
        ]);

        $data['actions'] = $this->actionManager->createActionsViewData($actions, $options['resource']);
        $data['actionsSecondary'] = $this->actionManager->createActionsViewData($actionsSecondary, $options['resource']);
        $data['tabs'] = $this->createTabViewData($tabs, $options['translation_domain']);
        $data['messages'] = $this->getFlashMessages();
        $data['modals'] = [];
        $data['cssClass'] = $this->util->getViewerOption('css_class', $configuration);
    }


    public function createTemplateData($options, ViewData $viewData, ViewData $templateData)
    {
        /** @var MetadataInterface $metadata */
        $metadata = $options['metadata'];

        $parameters->set('form', $form->createView());

        $label = $this->util->mergeConfig([
            $options['label'],
            $this->util->getViewerOption('label', $configuration)
        ]);

        $parameters->set('tabs', $tabs);

        $parameters->set('form_template', $this->util->mergeConfig([
            $options['form_template'],
            $this->util->getViewerOption('form.template', $configuration)
        ]));

        $parameters->set('form_action', $this->util->mergeConfig([
            sprintf('%s_%s_create', $metadata->getApplicationName(), $this->util->getUnderscoreName($metadata)),
            $options['form_action'],
            $this->util->getViewerOption('form.action', $configuration)
        ]));

        $parameters->set('form_action_parameters', $this->util->mergeConfigArray([
            $options['form_action_parameters'],
            $this->util->getViewerOption('form.action_parameters', $configuration)
        ]));

        $parameters->set('form_themes', $this->util->mergeConfigArray([
            $this->formThemes,
            $options['form_themes'],
            $this->util->getViewerOption('form.themes', $configuration)
        ]));
    }

    public function handleRequest($options, Request $request, ViewData $viewData, ViewData $templateData): Response
    {
        $this->util->updateRequest();
        $configuration = $this->util->getRequestConfiguration($options);
        $this->util->isGrantedOr403($configuration, ResourceActions::CREATE);
        $resourceFactory = $options['resource_factory'];
        $resourceFormFactory = $options['resource_form_factory'];
        $factory = $options['factory'];
        $eventDispatcher = $options['event_dispatcher'];
        $repository = $options['repository'];

        $newResource = $resourceFactory->create($configuration, $factory);

        $form = $resourceFormFactory->create($configuration, $newResource);

        $templateData['form'] = $form;

        if ($request->isMethod('POST')) {
            if($form->handleRequest($request)->isValid()) {
                $newResource = $form->getData();
                $eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
                $repository->add($newResource);
                $eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->flashBag->add('success', $this->translator->trans('form.message.success', [], 'EnhavoAppBundle'));
                $route = $configuration->getRedirectRoute(null);
                return new RedirectResponse($this->router->generate($route, [
                    'id' => $newResource->getId(),
                    'tab' => $request->get('tab'),
                    'view_id' => $request->get('view_id')
                ]));
            } else {
                $this->flashBag->add('error', $this->translator->trans('form.message.error', [], 'EnhavoAppBundle'));
                foreach($form->getErrors() as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }
            }
        }
    }

    private function createTabViewData($configuration, $translationDomain)
    {
        $data = [];
        foreach($configuration as $key => $tab) {
            $tabData = [];
            $tabData['label'] = $this->translator->trans($tab['label'], [], isset($tab['translation_domain']) ? $tab['translation_domain'] : $translationDomain);
            $tabData['key'] = $key;
            $tabData['fullWidth'] = isset($tab['full_width']) && $tab['full_width'] ? true : false;
            $data[] = $tabData;
        }
        return $data;
    }

    private function createActions()
    {
        $default = [
            'save' => [
                'type' => 'save',
            ]
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
        $optionsResolver->setDefaults([
            'tabs' => [],
            'entrypoint' => 'enhavo/app/form',
            'actions' => [],
            'actions_secondary' => [],
            'form' => null,
            'form_themes' => [],
            'form_action' => null,
            'form_action_parameters' => [],
            'form_template' => 'EnhavoAppBundle:View:tab.html.twig',
            'template' => 'admin/view/form.html.twig',
            'translation_domain' => 'EnhavoAppBundle',
            'label' => 'label.create',
            'request_configuration' => null,
            'metadata' => null,
            'resource_factory' => null,
            'resource_form_factory' => null,
            'factory' => null,
            'repository' => null,
            'event_dispatcher' => null,
        ]);
    }
}

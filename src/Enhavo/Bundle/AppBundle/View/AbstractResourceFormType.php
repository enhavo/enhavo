<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Grid\Grid;
use Enhavo\Bundle\AppBundle\Grid\GridManager;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\Type\AppViewType;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
abstract class AbstractResourceFormType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    protected ?ResourceInterface $resource = null;
    protected ?Grid $grid = null;

    public function __construct(
        private array $formThemes,
        private ActionManager $actionManager,
        private FlashBag $flashBag,
        private ViewUtil $util,
        private RouterInterface $router,
        private TranslatorInterface $translator,
        private ResourceManager $resourceManager,
        private GridManager $gridManager,
        private ResourceFormFactory $resourceFormFactory,
        private NormalizerInterface $normalizer,
        protected EventDispatcherInterface $eventDispatcher,
    ) {}

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function init($options)
    {
        $this->util->updateRequest();
        $this->resource = $this->createResource($options);

        $this->grid = new Grid();
        $this->buildGrid($options);
    }

    protected function buildGrid($options)
    {
        $configuration = $this->getRequestConfiguration($options);

        $this->grid->addActions($this->createActions($options));
        $this->grid->addActions($options['actions']);
        $this->grid->addActions($this->util->getViewerOption('actions', $configuration));

        $this->grid->addSecondaryActions($this->createSecondaryActions($options));
        $this->grid->addSecondaryActions($options['actions_secondary']);
        $this->grid->addSecondaryActions($this->util->getViewerOption('actions_secondary', $configuration));

        $this->grid->addTabs($this->createTabs($options));
        $this->grid->addTabs($options['tabs']);
        $this->grid->addTabs($this->util->getViewerOption('tabs', $configuration));

        $this->grid->setLabel($options['label']);
        $this->grid->setLabel($this->util->getViewerOption('label', $configuration));

        $this->grid->setFormTemplate($this->util->getViewerOption('form.template', $configuration));
        $this->grid->setFormTemplate($options['form_template']);

        $this->grid->setFormAction($this->getFormAction($options));
        $this->grid->setFormAction($options['form_action']);
        $this->grid->setFormAction($this->util->getViewerOption( 'form.action', $configuration));

        $this->grid->setFormActionParameters($this->getFormActionParameters($options));
        $this->grid->setFormActionParameters($options['form_action_parameters']);
        $this->grid->setFormActionParameters($this->util->getViewerOption('form.action_parameters', $configuration));

        $this->grid->addFormThemes($this->formThemes);
        $this->grid->addFormThemes($options['form_themes']);
        $this->grid->addFormThemes($this->util->getViewerOption('form.themes', $configuration));
    }

    public function createViewData($options, ViewData $viewData)
    {
        $requestConfiguration = $this->getRequestConfiguration($options);

        $viewData->set('actions', $this->actionManager->createActionsViewData($this->grid->getActions(), $this->resource));
        $viewData->set('actionsSecondary', $this->actionManager->createActionsViewData($this->grid->getSecondaryActions(), $this->resource));
        $viewData->set('tabs', $this->gridManager->createTabViewData($this->grid->getTabs(), $options['translation_domain']));
        $viewData->set('messages', $this->util->getFlashMessages());
        $viewData->set('modals', []);
        if ($requestConfiguration->getSerializationGroups()) {
            $viewData->set('resource', $this->normalizer->normalize($this->resource, null, ['groups' => $requestConfiguration->getSerializationGroups()]));
        }

    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $templateData->set('tabs', $viewData->get('tabs'));
        $templateData->set('resource', $this->resource);
        $templateData->set('form_template', $this->grid->getFormTemplate());
        $templateData->set('form_action', $this->grid->getFormAction());
        $templateData->set('form_action_parameters', $this->grid->getFormAction());
        $templateData->set('form_action_parameters', $this->grid->getFormActionParameters());
        $templateData->set('form_themes', $this->grid->getFormThemes());
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->getRequestConfiguration($options);

        $form = $this->resourceFormFactory->create($configuration, $this->resource);

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $this->resource = $form->getData();

                $response = $this->save($options);
                if ($response) {
                    return $response;
                }

                $this->flashBag->add('success', $this->translator->trans('form.message.success', [], 'EnhavoAppBundle'));
                $route = $this->getRedirectRoute($options) ?? $request->get('_route');
                return new RedirectResponse($this->router->generate($route, [
                    'id' => $this->resource->getId(),
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

        $response = $this->initialize($options);
        if ($response) {
            return $response;
        }

        $viewData['messages'] = array_merge($viewData['messages'], $this->util->getFlashMessages());
        $templateData['form'] = $form->createView();

        return null;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'tabs' => [],
            'application' => '@enhavo/app/form/FormApp',
            'component' => '@enhavo/app/form/components/FormComponent.vue',
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
        ]);

        $optionsResolver->setNormalizer('template', function(Options $options, $value) {
            $configuration = $this->util->getRequestConfiguration($options);
            return $configuration->getTemplate($value);
        });

        $optionsResolver->setRequired(['request_configuration']);
    }

    protected function createActions($options): array
    {
        return [
            'save' => [
                'type' => 'save',
            ]
        ];
    }

    protected function createSecondaryActions($options): array
    {
        return [];
    }

    protected function createTabs($options): array
    {
        $metadata = $this->getMetadata($options);
        return [
            'main' => [
                'label' => sprintf('%s.label.%s', $this->util->getUnderscoreName($metadata), $this->util->getUnderscoreName($metadata)),
                'template' => 'admin/view/form-template.html.twig'
            ],
        ];
    }

    abstract protected function save($options);
    abstract protected function initialize($options);
    abstract protected function createResource($options): ResourceInterface;

    abstract protected function getFormAction($options): string;
    abstract protected function getFormActionParameters($options): array;
    abstract protected function getRedirectRoute($options): ?string;
}

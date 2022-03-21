<?php
/**
 * TableViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\Viewer\ViewerUtil;
use Sylius\Component\Resource\Exception\DeleteHandlingException;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteViewType extends AbstractViewType
{
    public function __construct(
        ViewerUtil $util,
        FlashBag $flashBag,
        TranslatorInterface $translator
    ) {}

    public static function getName(): ?string
    {
        return 'delete';
    }

    public static function getParentType(): ?string
    {
        return AppViewType::class;
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::DELETE);
        $resource = $this->findOr404($configuration);

        if ($configuration->isCsrfProtectionEnabled() && !$this->isCsrfTokenValid((string) $resource->getId(), $request->request->get('_csrf_token'))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::DELETE, $configuration, $resource);

        if ($event->isStopped() && !$configuration->isHtmlRequest()) {
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }
        if ($event->isStopped()) {
            $this->flashHelper->addFlashFromEvent($configuration, $event);

            $eventResponse = $event->getResponse();
            if (null !== $eventResponse) {
                return $eventResponse;
            }

            return $this->redirectHandler->redirectToIndex($configuration, $resource);
        }

        try {
            $this->resourceDeleteHandler->handle($resource, $this->repository);
        } catch (DeleteHandlingException $exception) {
            if (!$configuration->isHtmlRequest()) {
                return $this->createRestView($configuration, null, $exception->getApiResponseCode());
            }

            $this->flashHelper->addErrorFlash($configuration, $exception->getFlash());

            return $this->redirectHandler->redirectToReferer($configuration);
        }

        if ($configuration->isHtmlRequest()) {
            $this->flashHelper->addSuccessFlash($configuration, ResourceActions::DELETE, $resource);
        }

        $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::DELETE, $configuration, $resource);

        if (!$configuration->isHtmlRequest()) {
            return $this->createRestView($configuration, null, Response::HTTP_NO_CONTENT);
        }

        $postEventResponse = $postEvent->getResponse();
        if (null !== $postEventResponse) {
            return $postEventResponse;
        }

        return $this->redirectHandler->redirectToIndex($configuration, $resource);

        if($response instanceof RedirectResponse) {
            $view = $this->viewFactory->create('delete', [
                'metadata' => $this->metadata,
                'request_configuration' => $configuration,
                'request' => $request
            ]);
            return $this->viewHandler->handle($configuration, $view);
        }
    }

    public function createViewData($options, ViewData $data)
    {
        $configuration = $this->util->getRequestConfiguration($options);

        $label = $this->util->mergeConfig([
            $options['label'],
            $this->util->getViewerOption('label', $configuration)
        ]);

        $viewerOptions = $configuration->getViewerOptions();
        $data['messages'] = $this->getFlashMessages();
        $data['view'] = [
            'id' => $this->getViewId(),
            'label' => $this->translator->trans($label, [], $viewerOptions['translation_domain'])
        ];
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $templateData['resource'] = $options['resource'];
    }

    private function getFlashMessages()
    {
        $messages = [];
        $types = ['success', 'error', 'notice', 'warning'];
        foreach($types as $type) {
            foreach($this->flashBag->get($type) as $message) {
                $messages[] = [
                    'message' => $this->translator->trans(is_array($message) ? $message['message'] : $message),
                    'type' => $type
                ];
            }
        }
        return $messages;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'entrypoint' => 'enhavo/app/delete',
        ]);
    }
}

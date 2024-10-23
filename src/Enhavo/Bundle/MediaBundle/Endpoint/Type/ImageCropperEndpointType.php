<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Action\MediaCropActionType;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Media\ImageCropperManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImageCropperEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly MediaManager $mediaManager,
        private readonly FormatManager $formatManager,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
        private readonly ActionManager $actionManager,
        private readonly RouterInterface $router,
        private readonly ImageCropperManager $imageCropperManager,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $format = $this->getFormat($options, $request);

        if ($request->getMethod() == Request::METHOD_POST) {
            if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('image_cropper', $request->getPayload()->get('token')))) {
                $context->setStatusCode(400);
                $data['success'] = false;
                $data['message'] = 'Invalid token';
                return;
            }

            $this->cropFormat($request, $format);
            $data['success'] = true;
            $data['message'] = null;
            return;
        }

        $data['format'] = $this->createFormatData($format);
        $data['token'] = $this->csrfTokenManager->getToken('image_cropper')->getValue();
        $data['actions'] = $this->getActions($format);
        $data['actionsSecondary'] = [];
        $data['label'] = $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    private function createFormatData(FormatInterface $format): array
    {
        $url = $this->router->generate('enhavo_media_admin_api_file', ['token' => $format->getFile()->getToken()]);
        $parameters = $format->getParameters();
        $ratio = $this->imageCropperManager->getFormatRatio($format);

        if (is_array($parameters)) {
            if(array_key_exists('cropHeight', $parameters) &&
                array_key_exists('cropWidth', $parameters) &&
                array_key_exists('cropX', $parameters) &&
                array_key_exists('cropY', $parameters))
            {
                return [
                    'height' => $parameters['cropHeight'],
                    'width' => $parameters['cropWidth'],
                    'x' => $parameters['cropX'],
                    'y' =>$parameters['cropY'],
                    'ratio' => $ratio,
                    'url' => $url
                ];
            }
        }

        return [
            'height' => null,
            'width' => null,
            'x' => null,
            'y' => null,
            'ratio' => $ratio,
            'url' => $url
        ];
    }

    protected function getActions(FormatInterface $format): array
    {
        $default = [
            'crop' => [
                'type' => MediaCropActionType::class,
                'label' => 'action.label.save',
                'translation_domain' => 'EnhavoResourceBundle',
                'icon' => 'save',
                'method' => 'crop'
            ],
            'zoom_in' => [
                'type' => MediaCropActionType::class,
                'label' => 'media.image_cropper.label.tooltip_zoom_in',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'add',
                'method' => 'zoomIn'
            ],
            'zoom_out' => [
                'type' => MediaCropActionType::class,
                'label' => 'media.image_cropper.label.tooltip_zoom_out',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'remove',
                'method' => 'zoomOut'
            ],
            'reset' => [
                'type' => MediaCropActionType::class,
                'label' => 'media.image_cropper.label.tooltip_reset',
                'translation_domain' => 'EnhavoMediaBundle',
                'icon' => 'reply',
                'method' => 'reset'
            ]
        ];

        $viewData = [];
        $actions = $this->actionManager->getActions($default);
        foreach ($actions as $key => $action) {
            $viewData[$key] = $action->createViewData($format);
        }
        return $viewData;
    }

    private function cropFormat(Request $request, FormatInterface $format): void
    {
        $payload = $request->getPayload();

        $height = intval($payload->get('height'));
        $width = intval($payload->get('width'));
        $x = intval($payload->get('x'));
        $y = intval($payload->get('y'));

        $parameters = $format->getParameters();
        if(!is_array($parameters)) {
            $parameters = [];
        }

        $parameters['cropHeight'] = $height;
        $parameters['cropWidth'] = $width;
        $parameters['cropX'] = $x;
        $parameters['cropY'] = $y;

        $format->setParameters($parameters);
        $this->entityManager->flush();

        $this->formatManager->applyFormat($format->getFile(), $format->getName(), $parameters);
    }

    private function getFormat($options, Request $request): FormatInterface
    {
        $token = $request->get('token');
        $format = $request->get('format');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        if ($file === null) {
            throw $this->createNotFoundException();
        }

        if ($options['permission'] && !$this->authorizationChecker->isGranted($options['permission'], $file)) {
            throw $this->createAccessDeniedException();
        }

        $format = $this->mediaManager->getFormat($file, $format);
        return $format;
    }

    public static function getName(): ?string
    {
        return 'media_image_cropper';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'media.image_cropper.label.image_cropper',
            'translation_domain' => 'EnhavoMediaBundle',
            'permission' => 'ROLE_ENHAVO_MEDIA_FILE_CREATE',
        ]);
    }
}

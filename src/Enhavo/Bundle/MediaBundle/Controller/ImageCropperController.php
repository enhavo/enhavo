<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.01.18
 * Time: 19:46
 */

namespace Enhavo\Bundle\MediaBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Component\Type\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImageCropperController extends AbstractController
{
    public function __construct(
        private ResourceManager $resourceManager,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private MediaManager $mediaManager,
        private FactoryInterface $viewFactory,
        private FormatManager $formatManager,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function indexAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->resourceManager->getMetadata('enhavo_media', 'file'), $request);

        $format = $this->getFormat($request);

        if($request->getMethod() == Request::METHOD_POST) {
            $this->cropFormat($request);
            $this->addFlash('success', $this->translator->trans('media.image_cropper.message.save', [], 'EnhavoMediaBundle'));
        }

        $view = $this->viewFactory->create([
            'type' => 'image_cropper',
            'request_configuration' => $configuration,
            'format' => $format,
        ]);

        return $view->getResponse($request);
    }

    private function cropFormat(Request $request)
    {
        $height = intval($request->get('height'));
        $width = intval($request->get('width'));
        $x = intval($request->get('x'));
        $y = intval($request->get('y'));

        $format = $this->getFormat($request);
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

    /**
     * @param Request $request
     * @return \Enhavo\Bundle\MediaBundle\Model\FormatInterface|null
     */
    private function getFormat(Request $request)
    {
        $token = $request->get('token');
        $format = $request->get('format');

        $file = $this->mediaManager->findOneBy([
            'token' => $token
        ]);

        $format = $this->mediaManager->getFormat($file, $format);
        return $format;
    }
}

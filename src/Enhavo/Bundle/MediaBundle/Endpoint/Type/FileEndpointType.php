<?php

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileEndpointType extends AbstractEndpointType
{
    use FileContentTrait;

    public function __construct(
        private readonly ?string $maxAge,
        private readonly ?string $streamingDisabled,
        private readonly ?string $streamingThreshold,
        private readonly MediaManager $mediaManager,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $file = $this->getFile($options, $request);

        $response = $this->createFileResponse($file, $request);
        $this->handleCaching($response);

        $context->set('file', $file);
        $context->setResponse($response);
    }

    private function getFile($options, Request $request): FileInterface
    {
        $findBy = [];
        foreach ($options['find_by'] as $key => $value) {
            if (is_string($key)) {
                $findBy[$key] = $request->get($value);
            } else {
                $findBy[$value] = $request->get($value);
            }
        }
        $file = $this->mediaManager->findOneBy($findBy);

        if ($file === null) {
            throw $this->createNotFoundException();
        }

        if ($options['checksum_test']) {
            $shortChecksum = $request->get($options['checksum_parameter']);
            if ($shortChecksum != substr($file->getMd5Checksum(), 0, $options['checksum_length'])) {
                throw $this->createNotFoundException();
            }
        }

        if ($options['permission'] && !$this->authorizationChecker->isGranted($options['permission'], $file)) {
            throw $this->createAccessDeniedException();
        }

        return $file;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'find_by' => ['id', 'filename'],
            'checksum_test' => true,
            'checksum_parameter' => 'shortMd5Checksum',
            'checksum_length' => 6,
            'permission' => 'ROLE_ENHAVO_MEDIA_FILE_SHOW'
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_file';
    }
}

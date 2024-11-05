<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\ResourceBundle\Endpoint\Type\ResourceIndexEndpointType;
use Symfony\Component\HttpFoundation\Request;

class MediaLibraryEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly MediaManager $mediaManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data['uploadUrl'] = $this->generateUrl('enhavo_media_library_admin_api_upload');
        $data['selectUrl'] = $this->generateUrl('enhavo_media_library_admin_api_select');

        $data['maxUploadSize'] = $this->mediaManager->getMaxUploadSize();
    }

    public static function getParentType(): ?string
    {
        return ResourceIndexEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'media_library_index';
    }
}

<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Collection;

use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\ResourceBundle\Collection\ResourceItem;
use Enhavo\Bundle\ResourceBundle\Collection\TableCollection;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQueryFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class MediaLibraryCollection extends TableCollection
{
    public function __construct(
        ResourceExpressionLanguage $expressionLanguage,
        FilterQueryFactory $filterQueryFactory,
        RequestStack $requestStack,
        RouterInterface $router,
        private MediaLibraryManager $mediaLibraryManager,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
        parent::__construct($expressionLanguage, $filterQueryFactory, $requestStack, $router);
    }


    protected function createItem($resource, array $context): ResourceItem
    {
        $item = parent::createItem($resource, $context);
        $item['previewImageUrl'] = $this->urlGenerator->generateFormat($resource, 'enhavoMediaLibraryThumb');
        $item['icon'] = $this->mediaLibraryManager->getContentTypeIcon($resource->getContentType());
        $item['label'] = $resource->getFilename();
        $item['suffix'] = $resource->getExtension();
        $item['type'] = $resource->getContentType();
        $item['date'] = $resource->getCreatedAt() ? $resource->getCreatedAt()->format('Y-m-d') : '';
        return $item;
    }
}

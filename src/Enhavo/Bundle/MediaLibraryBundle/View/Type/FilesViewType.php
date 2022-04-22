<?php
/**
 * @author blutze-media
 * @since 2022-04-12
 */

namespace Enhavo\Bundle\MediaLibraryBundle\View\Type;

use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilesViewType extends AbstractViewType
{
    public function __construct(
        private MediaLibraryManager $mediaLibraryManager,
        private ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        private RepositoryInterface $repository,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public static function getName(): ?string
    {
        return 'media_files';
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $options['request_configuration'];

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $files = $this->createFileList($resources);
        $pages = range(1, $resources->getNbPages(), 1);

        $templateData['files'] = $files;
        $templateData['pages'] = $pages;
        $templateData['page'] = $options['page'];
        $templateData['columns'] = $options['columns'];
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        return new JsonResponse([
            'files' => $templateData['files'],
            'page' => $templateData['page'],
            'pages' => $templateData['pages'],
            'columns' => $templateData['columns'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'request_configuration',
        ]);
        $resolver->setDefaults([
            'page' => 1,
            'pages' => 1,
            'columns' => [
                ['property' => 'filename', 'label' => 'Name'],
                ['property' => 'extension', 'label' => 'Suffix'],
                ['property' => 'contentType', 'label' => 'Type'],
                ['property' => 'createdAt', 'label' => 'Date'],
            ]
        ]);
    }

    private function createFileList(Pagerfanta $files): array
    {
        $items = [];

        /** @var File $file */
        foreach ($files as $file) {
            $items[] = [
                'id' => $file->getId(),
                'previewImageUrl' => $this->urlGenerator->generateFormat($file, 'enhavoMediaLibraryThumb'),
                'icon' => $this->mediaLibraryManager->getContentTypeIcon($file->getContentType()),
                'label' => $file->getFilename(),
                'suffix' => $file->getExtension(),
                'type' => $file->getContentType(),
                'date' => $file->getCreatedAt() ? $file->getCreatedAt()->format('Y-m-d') : '',
            ];
        }

        return $items;
    }
}

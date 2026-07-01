<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileReplaceEndpointType extends AbstractEndpointType
{
    use FileTrait;

    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly MediaManager $mediaManager,
        private readonly ValidatorInterface $validator,
        private readonly FileRepository $fileRepository,
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $file = $this->getFileByToken($request);
        $uploadedFile = $this->getUploadedFile($request);

        $errors = $this->getErrors($options, $uploadedFile);
        if (count($errors)) {
            $response = new JsonResponse([
                'success' => false,
                'errors' => $errors,
            ]);
            $context->setResponse($response);
        }

        $newFile = $this->fileFactory->createFromUploadedFile($uploadedFile);

        $file->setContent($newFile->getContent());
        $file->setMimeType($newFile->getMimeType());
        $file->setBasename($newFile->getBasename());

        $this->resourceManager->save($file);

        $context->setResponse($this->createFileResponse($file));
    }

    private function getFileByToken(Request $request): FileInterface
    {
        $token = $request->get('token');

        $file = $this->fileRepository->findOneBy([
            'token' => $token,
        ]);

        if (!$file) {
            throw $this->createNotFoundException();
        }

        return $file;
    }

    public function describe($options, Path $path): void
    {
        $path->method('post')
            ->tags(['enhavo_media.file'])
            ->parameter('token')
                ->in('query')
                ->required()
                ->description('Token of the file to replace')
                ->schema()->string()->end()->end()
            ->end()
            ->requestBody()
                ->required()
                ->content('multipart/form-data')
                    ->schema()
                        ->object()
                            ->property('file', 'string')
                                ->format('binary')
                                ->description('Replacement file')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->response('200')
                ->description('Replaced file')
                ->content()
                    ->schema()
                        ->ref('#/components/schemas/MediaFile')
                    ->end()
                ->end()
            ->end()
            ->response('400')
                ->description('Validation error')
                ->content()
                    ->schema()
                        ->object()
                            ->property('success', 'boolean')->end()
                            ->property('errors', 'array')
                                ->items()
                                    ->string()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->response('404')
                ->description('File not found')
            ->end()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['media_upload'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_file_replace';
    }
}

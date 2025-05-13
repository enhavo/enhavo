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
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileUploadEndpointType extends AbstractEndpointType
{
    use FileTrait;

    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly ResourceManager $resourceManager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $storedFiles = [];
        foreach ($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var $uploadedFile UploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    $errors = $this->getErrors($options, $uploadedFile);
                    if (count($errors)) {
                        $response = new JsonResponse([
                            'success' => false,
                            'errors' => $errors,
                        ]);
                        $context->setResponse($response);
                    }
                    $file = $this->fileFactory->createFromUploadedFile($uploadedFile);

                    $file->setGarbage($options['garbage']);

                    $this->resourceManager->save($file);
                    $storedFiles[] = $file;
                } catch (StorageException $exception) {
                    foreach ($storedFiles as $file) {
                        $this->resourceManager->delete($file);
                    }
                }
            }
        }

        $context->setResponse($this->createFileResponse($storedFiles));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'garbage' => true,
            'validation_groups' => ['media_upload'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_file_upload';
    }
}

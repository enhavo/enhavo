<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaLibraryUploadEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly FactoryInterface $itemFactory,
        private readonly ValidatorInterface $validator,
        private readonly ResourceManager $resourceManager,
        private readonly array $constraints,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data['success'] = true;
        $data['errors'] = [];

        $storedItems = [];
        foreach ($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var $uploadedFile UploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    $errors = $this->getErrors($uploadedFile);
                    if (count($errors)) {
                        $data['success'] = false;
                        $data['errors'] = $errors;
                        $context->setStatusCode(400);

                        return;
                    }

                    $file = $this->fileFactory->createFromUploadedFile($uploadedFile);
                    $errors = $this->getErrors($file, $this->constraints);
                    if (count($errors)) {
                        $data['success'] = false;
                        $data['errors'] = $errors;
                        $context->setStatusCode(400);

                        return;
                    }

                    /** @var ItemInterface $item */
                    $item = $this->itemFactory->createNew();
                    $item->setFile($file);
                    $file->setGarbage(false);
                    $this->resourceManager->save($item);
                    $storedItems[] = $file;
                } catch (StorageException $exception) {
                    foreach ($storedItems as $item) {
                        $this->resourceManager->delete($item);
                    }
                }
            }
        }
    }

    private function getErrors(mixed $value, $constraints = []): array
    {
        $result = [];
        $errors = $this->validator->validate($value, $this->createConstraints($constraints));
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $result[] = $error->getMessage();
            break;
        }

        return $result;
    }

    private function createConstraints(array $constraints): array
    {
        $data = [];
        foreach ($constraints as $constraint) {
            $class = is_string($constraint) ? $constraint : array_keys($constraint)[0];
            $options = is_string($constraint) ? [] : $constraint[array_keys($constraint)[0]];
            $options = null === $options ? [] : $options;

            $data[] = new $class(...$options);
        }

        return $data;
    }

    public static function getName(): ?string
    {
        return 'media_library_upload';
    }
}

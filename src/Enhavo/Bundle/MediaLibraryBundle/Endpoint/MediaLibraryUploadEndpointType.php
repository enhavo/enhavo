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
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaLibraryUploadEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly FileFactory $fileFactory,
        private readonly ItemRepository $itemRepository,
        private readonly ValidatorInterface $validator,
        private readonly ResourceManager $resourceManager,
        private readonly MediaLibraryManager $mediaLibraryManager,
        private readonly array $constraints,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data['success'] = true;
        $data['errors'] = [];

        $storedFiles = [];
        foreach ($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            /** @var UploadedFile $uploadedFile */
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
                    $errors = $this->getErrors($file, $this->constraints, $options['validation_groups']);
                    if (count($errors)) {
                        $data['success'] = false;
                        $data['errors'] = $errors;
                        $context->setStatusCode(400);

                        return;
                    }

                    if ($options['replace']) {
                        /** @var ItemInterface $item */
                        $item = $this->itemRepository->find($request->query->get('id'));
                        $this->mediaLibraryManager->replaceFile($item, $file);
                    } else {
                        $item = $this->mediaLibraryManager->createItem($file);
                    }

                    $this->resourceManager->save($item);
                    $storedFiles[] = $file;
                } catch (StorageException $exception) {
                    foreach ($storedFiles as $item) {
                        $this->resourceManager->delete($item);
                    }
                }
            }
        }
    }

    private function getErrors(mixed $value, $constraints = [], array $validationGroups = []): array
    {
        $result = [];
        $errors = $this->validator->validate($value, $this->createConstraints($constraints), $validationGroups);
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'replace' => false,
            'validation_groups' => ['media_upload'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'media_library_upload';
    }
}

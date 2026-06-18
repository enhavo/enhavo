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

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryApplyEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        /** @var ItemInterface $resource */
        $resource = $input->getResource();

        if (null === $resource) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted($input->getPermission($options['permission']), $resource);

        /** @var LibraryFileInterface $file */
        $file = $resource->getFile();
        $used = $resource->getUsedFiles();
        /** @var LibraryFileInterface $usedFile */
        foreach ($used as $usedFile) {
            $usedFile->setBasename($file->getBasename());
            $usedFile->setParameters($file->getParameters());
        }

        $this->entityManager->flush();

        $viewData = $input->getViewData($resource);
        $data->add($viewData);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission' => Permission::UPDATE,
        ]);

        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'media_library_apply';
    }
}

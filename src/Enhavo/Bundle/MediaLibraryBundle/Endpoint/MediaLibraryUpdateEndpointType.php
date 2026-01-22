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
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Endpoint\Type\ResourceUpdateEndpointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaLibraryUpdateEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        if ($options['update_all']) {
            $resource = $context->getData()['resource'];
            /** @var FileInterface $file */
            $file = $resource->getFile();
            $used = $resource->getUsedFiles();
            /** @var FileInterface $usedFile */
            foreach ($used as $usedFile) {
                $usedFile->setBasename($file->getBasename());
                $usedFile->setParameters($file->getParameters());
            }

            $this->entityManager->flush();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'update_all' => false,
        ]);
    }

    public static function getParentType(): string
    {
        return ResourceUpdateEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'media_library_update';
    }
}

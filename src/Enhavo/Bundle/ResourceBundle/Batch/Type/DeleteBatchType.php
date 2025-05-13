<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Batch\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        foreach ($ids as $id) {
            $resource = $repository->find($id);
            if ($resource) {
                $this->resourceManager->delete($resource);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'batch.delete.label',
            'confirm_message' => 'batch.delete.message.confirm',
            'translation_domain' => 'EnhavoResourceBundle',
        ]);
    }

    public static function getName(): ?string
    {
        return 'delete';
    }
}

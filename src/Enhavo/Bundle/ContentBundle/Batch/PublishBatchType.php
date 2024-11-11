<?php

namespace Enhavo\Bundle\ContentBundle\Batch;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublishBatchType extends AbstractBatchType
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        foreach ($ids as $id) {
            $resource = $repository->find($id);
            if ($resource instanceof Publishable) {
                $resource->setPublic(true);
            }
        }
        $this->em->flush();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'batch.publish.label',
            'confirm_message' => 'batch.publish.message.confirm',
            'translation_domain' => 'EnhavoContentBundle',
        ]);
    }

    public static function getName(): ?string
    {
        return 'publish';
    }
}

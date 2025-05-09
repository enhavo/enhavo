<?php

namespace Enhavo\Bundle\RevisionBundle\Batch\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\Delete\DeleteHandlerInterface;
use Enhavo\Bundle\RevisionBundle\Entity\Bin;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BinDeleteBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly DeleteHandlerInterface $deleteHandler,
        private readonly EntityManagerInterface $em,

    )
    {
    }

    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        $this->em->getFilters()->disable('revision');
        foreach ($ids as $id) {
            $resource = $repository->find($id);

            if ($resource instanceof Bin) {
                $this->deleteHandler->delete($resource);
            }
        }
        $this->em->getFilters()->enable('revision');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'batch.delete.label',
            'confirm_message' => 'batch.delete.message.confirm',
            'translation_domain' => 'EnhavoResourceBundle',
        ]);
    }
}

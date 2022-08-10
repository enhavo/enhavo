<?php

namespace Enhavo\Bundle\ContentBundle\Batch;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PublishType.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class PublishBatchType extends AbstractBatchType
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PublishBatchType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $options
     * @param Publishable[] $resources
     * @return Response|null
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null): ?Response
    {
        foreach($resources as $resource) {
            $resource->setPublic(true);
        }
        $this->em->flush();
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
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

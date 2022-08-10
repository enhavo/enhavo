<?php

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * DeleteType.php
 *
 * @since 04/07/16
 * @author gseidel
 */
class DeleteBatchType extends AbstractBatchType
{
    /** @var ResourceManager */
    private $resourceManager;

    /**
     * DeleteBatchType constructor.
     * @param ResourceManager $resourceManager
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }


    /**
     * @inheritdoc
     */
    public function execute(array $options, array $resources, ResourceInterface $resource = null): ?Response
    {
        foreach($resources as $resource) {
            $this->resourceManager->delete($resource);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function createViewData(array $options, ViewData $data, ResourceInterface $resource = null)
    {
        $data['route'] = $options['route'];
        $data['routeParameters'] = $options['route_parameters'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'batch.delete.label',
            'confirm_message' => 'batch.delete.message.confirm',
            'translation_domain' => 'EnhavoAppBundle',
            'route' => null,
            'route_parameters' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'delete';
    }
}

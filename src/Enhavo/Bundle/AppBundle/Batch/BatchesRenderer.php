<?php

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BatchesRenderer extends AbstractRenderer implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function render($batchesArray)
    {
        $batches = [];
        foreach($batchesArray as $batchOption) {
            /** @var BatchInterface $batch */
            $batch = $this->getType($batchOption['type']);
            $batch = clone $batch;
            $batch->setOptions($batchOption);
            $batches[] = $batch;
        }

        return $this->container->get('templating')->render('EnhavoAppBundle:Batch:batches.html.twig', [
            'batches' => $batches
        ]);
    }

    public function getName()
    {
        return 'batches_render';
    }
}
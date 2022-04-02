<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Component\Type\FactoryInterface;

trait ResourceControllerTrait
{
    protected ?FactoryInterface $viewFactory;
    protected ?AppEventDispatcher $appEventDispatcher;
    protected BatchManager $batchManager;

    /**
     * @param FactoryInterface $viewFactory
     */
    public function setViewFactory(FactoryInterface $viewFactory): void
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @param AppEventDispatcher $appEventDispatcher
     */
    public function setAppEventDispatcher(AppEventDispatcher $appEventDispatcher): void
    {
        $this->appEventDispatcher = $appEventDispatcher;
    }

    /**
     * @param BatchManager $batchManager
     */
    public function setBatchManager(BatchManager $batchManager): void
    {
        $this->batchManager = $batchManager;
    }

}

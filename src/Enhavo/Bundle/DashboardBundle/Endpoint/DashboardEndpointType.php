<?php

namespace Enhavo\Bundle\DashboardBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardManager;
use Symfony\Component\HttpFoundation\Request;

class DashboardEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly DashboardManager $dashboardManager,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data->set('widgets', $this->dashboardManager->createViewData());
    }
}

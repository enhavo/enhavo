<?php

namespace Bundle\MultiTenancyBundle\EventListener;

use Bundle\MultiTenancyBundle\Resolver\MultiTenancyResolver;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MultiTenancyListener
{
    /**
     * @var MultiTenancyResolver
     */
	private $resolver;

	public function __construct(MultiTenancyResolver $resolver)
    {
		$this->resolver = $resolver;
	}

	/**
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
    {
        $this->resolver->resolve($event->getRequest());
	}

}

<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;

use Symfony\Component\HttpFoundation\Request;
use esperanto\AdminBundle\Exception\ViewerNotFoundException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ViewerFactory
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var array
     */
    protected $list;

    public function __construct(Container $container, RequestStack $requestStack, $viewerList)
    {
        $this->container = $container;
        $this->requestStack = $requestStack;
        $this->list = $viewerList;
    }

    public function create($type, $default = null)
    {
        $request = $this->getRequest();
        try {
            $class = $this->matchViewer($type, $default);
        } catch(ViewerNotFoundException $e) {
            throw new ViewerNotFoundException(sprintf(
                '%s. Using route "%s"',
                $e->getMessage(),
                $request->get('_route'))
            );
        }

        /** @var $viewer AbstractViewer */
        $viewer = new $class;
        if($viewer instanceof ContainerAwareInterface) {
            $viewer->setContainer($this->container);
        }
        $viewer->setRequest($request);
        return $viewer;
    }

    /**
     * @return null|Request
     */
    protected function getRequest()
    {
        return $this->requestStack->getMasterRequest();
    }

    protected function matchViewer($type, $default = null)
    {
        if(isset($this->list[$type])) {
            return $this->list[$type];
        }

        if(isset($this->list[$default])) {
            return $this->list[$default];
        }

        throw new ViewerNotFoundException(sprintf('Trying to match viewer by type "%s" or default "%s" but no viewer found', $type));
    }
}
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

class ViewerFactory
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create($type)
    {
        $request = $this->getRequest();
        try {
            $class = $this->matchViewer($type);
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

    public function getRequest()
    {
        return $this->container->get('request_stack')->getMasterRequest();
    }

    public function matchViewer($type)
    {
        $list = array(
            'viewer.table' => 'esperanto\AdminBundle\Viewer\TableViewer',
            'viewer.create' => 'esperanto\AdminBundle\Viewer\CreateViewer',
            'viewer.index' => 'esperanto\AdminBundle\Viewer\IndexViewer',
            'viewer.edit' => 'esperanto\AdminBundle\Viewer\EditViewer'
        );

        if(isset($list[$type])) {
            return $list[$type];
        }

        throw new ViewerNotFoundException(sprintf('Trying to match viewer by type "%s" but no viewer found', $type));
    }
}
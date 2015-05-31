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

    public function create(Request $request)
    {
        $class = $this->matchViewer($request);
        /** @var $viewer AbstractViewer */
        $viewer = new $class;
        if($viewer instanceof ContainerAwareInterface) {
            $viewer->setContainer($this->container);
        }
        $viewer->setRequest($request);

        return $viewer;
    }

    public function matchViewer(Request $request)
    {
        $viewer = $request->get('_viewer');
        $name = $viewer['viewer'];
        $list = array(
            'viewer.table' => 'esperanto\AdminBundle\Viewer\TableViewer',
            'viewer.create' => 'esperanto\AdminBundle\Viewer\CreateViewer',
            'viewer.index' => 'esperanto\AdminBundle\Viewer\IndexViewer',
            'viewer.edit' => 'esperanto\AdminBundle\Viewer\EditViewer'
        );

        if(isset($list[$name])) {
            return $list[$name];
        }

        $route = $request->get('_route');
        throw new ViewerNotFoundException(sprintf('Trying to match viewer by name "%s" with route "%s"', $name, $route));
    }
}
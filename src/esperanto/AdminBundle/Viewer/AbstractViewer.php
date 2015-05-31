<?php
/**
 * AbstractViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Viewer;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractViewer implements ContainerAwareInterface
{
    private $resource;

    private $container;

    private $request;

    private $form;

    /**
     * @param mixed $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    public function dispatchEvent()
    {

    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getParameters()
    {
        $parameters = array(
            'viewer' => $this,
            'table_route' => 'esperanto_page_page_table',
            'create_route' => 'esperanto_page_page_create',
            'delete_route' => 'esperanto_page_page_delete',
            'data' => $this->getResource(),
            'add_button_text' => 'label.button',
            'edit_route' => 'esperanto_page_page_update',
            'table_template' => 'esperantoAdminBundle:Resource:table.html.twig',
            'blocks' => array(
                'table' => array(
                    'type' => 'table',
                    'parameters' => array(
                        'table_route' => 'esperanto_page_page_table',
                        'update_route' => 'esperanto_page_page_update'
                    )
                ),
            ),
            'preview_route' => null,
            'form_template' => 'esperantoAdminBundle:View:tab.html.twig',
            'tabs' => array(
                array(
                    'label' => 'page',
                    'template' => 'esperantoPageBundle:Tab:page.html.twig'
                ),
                array(
                    'label' => 'content',
                    'template' => 'esperantoPageBundle:Tab:content.html.twig'
                ),
                array(
                    'label' => 'seo',
                    'template' => 'esperantoPageBundle:Tab:seo.html.twig'
                )
            ),
            'columns' => array(
                'id' => array(
                    'label' => 'label.id',
                    'property' => 'id',
                    'width' => 1
                ),
                'title' => array(
                    'label' => 'label.title',
                    'property' => 'title',
                    'width' => 1
                ),
                'public' => array(
                    'label' => 'label.public',
                    'property' => 'public',
                    'width' => 1
                ),
            ),
            'form' => $this->getForm()
        );

        return $parameters;
    }

    public function getTheme()
    {
        return '::admin.html.twig';
    }

    public function getProperty($resource, $property)
    {
        $method = sprintf('get%s', ucfirst($property));
        $value = call_user_func(array($resource, $method));
        return $value;
    }
}
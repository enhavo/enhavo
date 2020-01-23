Dynamic routing
===============

.. note::

  This article outdated and may contain information that are not in use any more

The ``SymfonyCMF RoutingBundle`` is used to add optional dynamic routing for the resources. Usually dynamic routing
is used for the front end to have a nice seo url.

This following example will show you how to add a url field to your entity and how you map it to a controller action.

Relation
--------

First we need to tell our application that an entity has a ``manyToOne`` relation to a route. We use yaml
for the meta data.

.. code-block:: yaml

    manyToOne:
        route:
            cascade: ['persist', 'refresh', 'remove']
            targetEntity: Enhavo\Bundle\AppBundle\Entity\Route

.. note::

    Logically, the relation between the entity and route should be ``oneToOne``, but we have some problems with the
    doctrine configuration of the owning entity in this case. When using a ``manyToOne`` relation and only ever using
    a single entry, the owning side is clear.

Here is the php code for the entity class.

.. code-block:: php

    <?php

    //...

    /**
     * @var \Enhavo\Bundle\AppBundle\Entity\Route
     */
    private $route;

    /**
     * @return \Enhavo\Bundle\AppBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param \Enhavo\Bundle\AppBundle\Entity\Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    //...


Service
-------

Did you wonder why we don`t need to add relation information to the route configuration? That is because we use a
dynamic relation. The content of a route could be any entity. So we only save some type information, and when the route
tries to access its content, it is loaded on demand.

For this to work, we need to give the application some mapping information. This will be done by creating a service.

.. code-block:: yaml

    parameters:
        enhavo_page.page.route_content_loader.class: Enhavo\AdminBundle\Route\RouteContentLoader

    services:
        enhavo_page.page.route_content_loader:
            class: '%enhavo_page.page.route_content_loader.class%'
            arguments:
                - 'enhavo_page.page'
                - '%enhavo_page.model.page.class%'
                - 'enhavo_page.repository.page'
            tags:
                - { name: enhavo_route_content_loader }

.. note::

    The third argument is the name of the service, but not the service directly.
    The ``@`` here is missing, because we need some lazy load for getting the service.
    If we don`t do this, we have some loops in our dependencies.

Form
----

To add an url field in our form we just use this simple snippet.
There is already a form type ``enhavo_route``, which handle
all we need. Also the contraints, so we use a clean and unique url.

.. code-block:: php

    <?php

    $builder->add('route', 'enhavo_route');

If you render your form manually, you shouln't forget to add it in your template file.

.. code-block:: twig

    {{ form_row(form.route) }}

Controller
----------

And last but not least, we have to define our controller, and add some
mapping information to the ``SymfonyCMF RoutingBundle``. The mapping contains
the class name of our entity and the action which should be called for it.

.. code-block:: yaml

    cmf_routing:
        dynamic:
            controllers_by_class:
                enhavo\ProjectBundle\Entity\Page: enhavoProjectBundle:Main:page

In our yaml we use ``enhavoProjectBundle:Main:page`` as action, so we also have to add this to
our Controller.

.. code-block:: php

    <?php

    public function pageAction(Page $contentDocument)
    {
        return $this->render('enhavoProjectBundle:Page:page.html.twig', array(
            'page' => $contentDocument
        ));
    }

.. note::

    The first parameter name for the action must be named ``$contentDocument``.
    Otherwise you will get some errors.

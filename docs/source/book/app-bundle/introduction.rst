Introduction
============

What is the AppBundle?
----------------------

The AppBundle provides a handful of useful functions and workflows, helping you to handle the input and output of any
model. The AppBundle does not depend on a concrete model. It handles a model in a very abstract manner.

In a normal web application, we expect to have something like an MVC architecture. We would define our model,
controller and our view. If we do it the Symfony way, we also define a form type and add the routing for our controller.
The AppBundle provides controller functionality and views, leaving only model/form definition and routing.

The big magic is all in the route. Enhavo (and Sylius) use the routes ``default`` section for configuration parameters,
allowing you to configure the controllers behaviour and views without having to write them yourself.

This bundle provides you with standard ways of handling your models in a CRUD fashion, allowing you to easily implement
your custom models in a CRUD fashion and with a consistent look and feel. And if you have any requirements which the
bundle does not provide, we still have a good API to easily add these yourself.

The default way to handle your data
-----------------------------------

Ok, lets do some simple examples to see what the benefits are for your application.
Imagine a library, where we have books. First, we would add our model.

.. code-block:: php

    <?php

        namespace ProjectBundle\Entity

        class Book {
            private $author;
            private $title;
            function setAuthor() { /* you know what to do here */ }
            //setters, getters
        }

If we have a model, we also need the FormType

.. code-block:: php

    <?php

        namespace ProjectBundle\Form\Type

        class BookType extends AbstractType {
            public function builder //..
        }


Now, you add your controller. Of course our controller need the basic CRUD functions.

.. code-block:: php

    <?php

        namespace ProjectBundle\Controller

        class BookController extends Controller
        {
            public function createAction() {  /* you know what to do here */ }
            public function readAction()   {  /* you know what to do here */ }
            public function updateAction() {  /* you know what to do here */ }
            public function deleteAction() {  /* you know what to do here */ }
        }

After this we write the views.

.. code-block:: twig

    {% block create %}
        {{ form }}
    {% endblock %}

    {% block read %}
        {{ form }}
    {% endblock %}

    {% block update %}
        {{ form }}
    {% endblock %}

    {% block delete %}
        {{ form }}
    {% endblock %}

And last but not least, we add the routing.

.. code-block:: yaml

    project_book_create:
        path: /project/book/create
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:create

    project_book_read:
        path: /project/book/read
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:read

    project_book_update:
        path: /project/book/update
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:update

    project_book_delete:
        path: /project/book/delete
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:delete

After this we can add and show the book model.

It's a common way, and if we have a whole bunch of models, this will be much copy and paste work.
So this is where the AppBundle wants to help. Reduce code and define a standard workflow to CRUD your data.
This will reduce your code and keep your view clean.

How we can do this shorter
--------------------------

The question is, where can we add a standard workflow to reduce duplicated code, without losing flexibility.
The answer is: the controller and the views. They usually are pretty identical for all models. So now we just leave
out the part of the view and controller. Instead we add our model to the configuration file and update our routes.

.. code-block:: yaml

    // app/config/config.yml

    // ...

    sylius_resource:
        resources:
            project.book:
                driver: doctrine/orm
                object_manager: default
                templates: project:Book
                classes:
                    model: ProjectBundle\Model\Book
                    controller: ProjectBundle\Controller\BookController


.. code-block:: yaml

    //routing
    project_book_create:
        path: /project/book/create
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:create

    project_book_read:
        path: /project/book/read
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:read

    project_book_update:
        path: /project/book/update
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:update

    project_book_delete:
        path: /project/book/delete
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:delete

Instead of implementing a controller, we just create an empty class and extend AppBundles ResourceController.

.. code-block:: php
    <?php
    // namespace/uses
    class BookController extends ResourceController
    {
    }

Maybe you ask yourself, why can't we add the routing dynamically, this is also copy and paste work? Yes, that's correct.
But we need the routing for our configuration to maintain flexibility.

There are some other bundles, like the SonataAdminBundle, that also add default routing. But what about if we,
for example, don't want a delete route? Or we want to use a different template or form? Then we would have to do
some work, extending the controller and overwriting functions.

This is what we want to avoid. We want to configure all of these things, and we want to do it in an intuitive place.
And in our opinion, this is the route. So for example, if we want to change the form template, we just pass this
information to the route definition.

.. code-block:: yaml

    //routing
    project_book_create:
        path: /project/book/create
        methods: [GET]
        defaults:
            _controller: ProjectBundle:Book:create
            _viewer:
                form:
                    template: ProjectBundle:Book:form.html.twig

Of course the route provides much more features and options. This should only give you an idea what this bundle wants
to do and where it can help you doing your work. The next chapters will give you a deeper understanding in what you
can do with the AppBundle.


Introduction
============

Symfony based
-------------

First of all we have to say that enhavo is based on the fullstack symfony framework. When we started to create enhavo
symfony 2.2 was released. There are a lot of php frameworks out there and we wanted to use a framework we can build
our CMS on. After some research and tryouts we decided to build it on top of symfony, because we liked the modular approach
and the awesome dependency injection. So we wanted to go on with the symfony philosophy and try to build our CMS features
as modules and build more modules that based on our modules we already created.

If you are familiar with symfony and its structure, you will feel comfortable working on enhavo.

Sylius based
------------

When we say enhavo is based on sylius we mean that enhavo is based on the SyliusResourceBundle. If you want to know more
about this Bundle you can read the docs of Sylius_

The SyliusResourceBundle provide a very abstract and powerfull CRUD Controller. So we don't need any CRUD Controller
in our modules. We can focus ourself to the model and form. A lot of customization will take place while defining the
route. We think that make sense, because the route definition is the entry point of every request in our application.
It takes care of our controller we wan't use, so why not inject more configuration and skip the controller.

.. _Sylius: https://docs.sylius.com/en/1.6/components_and_bundles/bundles/SyliusResourceBundle/

Bring your own domain model
---------------------------

The core functionality of enhavo provide a user interface with a user friendly grid and form. The core don't
know anything about a concrete model. Either the most of the bundles have no idea how a page or ar article have to look like.
Normally that are classic domain models a CMS depends on. We also have suggestion how a page and its content is structured,
but you don't need to use it. Instead bring your own model and enhavo is helping you to manage it.

We belive that a good CMS should know the model and not the model should know the CMS. This is a very big different and
has a impact to your whole architecture. In the end that means for you, that you can easily switch the CMS, because
a lot of functionality has no dependencies to enhavo. This approach is the central statement of domain driven design.
Keep your domain away from dependencies you don't have your hands on! So you have already isolated your domain?
Just bring it to enhavo, again, we provide you a powerfully user interface and functionality that may useful for you.



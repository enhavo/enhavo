## Introduction

### What is the AppBundle?

The AppBundle provides a handful of useful functions and workflows,
helping you to handle the input and output of any model. The AppBundle
does not depend on a concrete model. It handles a model in a very
abstract manner.

In a normal web application, we expect to have something like an MVC
architecture. We would define our model, controller and our view. If we
do it the Symfony way, we also define a form type and add the routing
for our controller. The AppBundle provides controller functionality and
views, leaving only model/form definition and routing.

The big magic is all in the route. Enhavo (and Sylius) use the routes
`default` section for configuration parameters, allowing you to
configure the controllers behaviour and views without having to write
them yourself.

This bundle provides you with standard ways of handling your models in a
CRUD fashion, allowing you to easily implement your custom models in a
CRUD fashion and with a consistent look and feel. And if you have any
requirements which the bundle does not provide, we still have a good API
to easily add these yourself.

### Read more

* [Data handling in enhavo](../general/data-handling.md)
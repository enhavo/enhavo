Bundle Structure
================

Dependency philosophy
---------------------

If you check the bundle structure of other symfony based systems like Sonata, Sulu or Sylius,
you will recognize that there are two major approaches out there.

.. image:: /_static/image/bundle-structure-core.png

A common approach is to have one CoreBundle (The name can vary from system to system), which has dependencies
to lot of other bundles. The big advantage is, because this CoreBundle knows everything, you can easily write code
that combines the bundles. This will lead to a fat bundle which melt your code together, but also leave the other bundles
small and therefore in most cases more reusable for other projects.


.. image:: /_static/image/bundle-structure-app.png

The other approach, which enhavo is using, is to have a AppBundle (We call it AppBundle.
Other project call it CoreBundle as well) which has no dependencies to the other bundles
of the system. Instead, the other bundles know the AppBundle and its functionality.
The advantage here is, that you can decide, which bundle you will add to your project
and which not. The structure is more flexible and modular, but on the other hand, bundles can't reused in other
project than enhavo, because the implementation need the base functionality of the AppBundle.


Layers
------

If we have a look at enhavo bundles dependency graph, we can recognize, that the bundles can be divided into three categories
or layers. The characteristic of a layer is that the dependencies inside the layer is less, while the dependencies between
the layers are very high.

**Core Layer:**

The core layer provide core functionality to show the admin interface and helpers to manage your models.

**Functionality Layer:**

The functionality layer provide useful functionality and address a special problem. In most cases they don't make sense to use standalone.

**Domain Layer:**

The Domain layer uses a lot features from the functionality layer. Each bundle has suggestion how to organize its model
and provide special functionality for it.


Here you can see all bundles divided into its layers

.. image:: /_static/image/bundle-structure-layer.png





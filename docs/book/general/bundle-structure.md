# Bundle Structure

## Dependency philosophy

If you check the bundle structure of other symfony based systems like
Sonata, Sulu or Sylius, you will recognize that there are two major
approaches out there.

![image](/images/bundle-structure-core.png)

A common approach is to have one CoreBundle (The name can vary from
system to system), which has dependencies to lot of other bundles. The
big advantage is, because this CoreBundle knows everything, you can
easily write code that combines the bundles. This will lead to a fat
bundle which melts your code together, but also leave the other bundles
small and therefore in most cases more reusable for other projects.

![image](/images/bundle-structure-app.png)

The other approach, which enhavo is using, is to have a Corebundle (We
call it AppBundle) which has no dependencies to the other bundles of the
system. Instead, the other bundles know the AppBundle and its
functionality. The advantage here is, that you can decide, which bundle
you will add to your project and which not. The structure is more
flexible and modular, but on the other hand, bundles can\'t be reused in
other projects than enhavo, because the implementation needs the base
functionality of the AppBundle.

## Layers

If we have a look at enhavo bundles\' dependency graph, we see that the
bundles can be divided into three categories or layers. The
characteristic of a layer is that inside the layer there are less
dependencies, while the dependencies between the layers are very
frequent.

**Core Layer:**

The core layer provides core functionality to show the admin interface,
and helpers to manage your models.

**Functionality Layer:**

The functionality layer provides useful functionality to address
specific problems. In most cases they don\'t make sense to use on their
own.

**Domain Layer:**

The Domain layer uses a lot of features from the functionality layer.
Each bundle is a suggestion on how to address a specific common use case
and provides special functionality for it.

Here you can see all the bundles divided into these layers

![image](/images/bundle-structure-layer.png)

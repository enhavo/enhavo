Migrate to 0.9
==============

1. Add this line at the end of ``assets/enhavo/form.ts``

.. code:: js

    Application.getView().checkUrl();


2. Create registry file ``assets/enhavo/registry/widget.ts``

.. code::

    import RegistryPackage from "@enhavo/core/RegistryPackage";
    import ApplicationInterface from "@enhavo/app/ApplicationInterface";
    import AppWidgetRegistryPackage from "@enhavo/app/Toolbar/Widget/WidgetRegistryPackage";

    export default class ViewRegistryPackage extends RegistryPackage
    {
        constructor(application: ApplicationInterface) {
            super();
            this.registerPackage(new AppWidgetRegistryPackage(application));
        }
    }

3. Add widget registry to ``assets/enhavo/main.ts``

.. code::

    // Add import line
    import WidgetRegistryPackage from "./registry/widget";

    // Add register package line before load
    Application.getWidgetRegistry().registerPackage(new WidgetRegistryPackage(Application));

    Application.getVueLoader().load(() => import("@enhavo/app/Main/Components/MainComponent.vue"));

4. Rename routing manager service id ``enhavo_routing.manager.route`` to FQCN ``Enhavo\Bundle\RoutingBundle\Manager\RouteManager``

5. Rename ``property`` to ``properties`` for route PrefixGenerator type.

.. code:: yaml

  enhavo_routing:
      classes:
          App\MyEntity:
              generators:
                  prefix:
                      type: prefix
                      # before
                      property: title
                      # now
                      properties: title

6. If the action component ``create-action`` was used, you have to migrate it to ``open-action``. `
The action type ``create`` is not affected.

7. Add form registry to ``assets/enhavo/index.ts``

.. code::

    // Add import line
    import FormRegistryPackage from "./registry/form";

    // Add register package line before load
    Application.getFormRegistry().registerPackage(new FormRegistryPackage(Application));

   Application.getVueLoader().load(() => import("@enhavo/app/Index/Components/IndexComponent.vue"));

8. Add resource parameter to Action

This changes only may apply if you have this functions define by your own

.. code::

    // change this line
    public function getPermission(array $options);

    // to this line
    public function getPermission(array $options, $resource = null);


    // change this line
    public function isHidden(array $options);

    // to this line
    public function isHidden(array $options, $resource = null);


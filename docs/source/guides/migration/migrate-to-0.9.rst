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

9. Update your ``webpack.config.js``. The way how to include other bundles and configure
your webpack/encore has changed. Just use
the following lines if you never edit your ``webpack.config.js`` .
If you edit this file before, you need to add the configs inside the js callbacks.

.. code:: js

    const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
    const AppPackage = require('@enhavo/app/Encore/EncoreRegistryPackage');
    const FormPackage = require('@enhavo/form/Encore/EncoreRegistryPackage');
    const MediaPackage = require('@enhavo/media/Encore/EncoreRegistryPackage');
    const DashboardPackage = require('@enhavo/dashboard/Encore/EncoreRegistryPackage');
    const UserPackage = require('@enhavo/user/Encore/EncoreRegistryPackage');

    EnhavoEncore
      // register packages
      .register(new AppPackage())
      .register(new FormPackage())
      .register(new MediaPackage())
      .register(new DashboardPackage())
      .register(new UserPackage())
    ;

    EnhavoEncore.add('enhavo', (Encore) => {
      // custom encore config
      // Encore.enableBuildNotifications();
    });

    EnhavoEncore.add('theme', (Encore) => {
      Encore
        // add theme entry and config
        .addEntry('base', './assets/theme/base')
    });

    module.exports = EnhavoEncore.export();


10. Change newsletter routing ``config/routes/enhavo_newsletter.yaml``

.. code:: yaml

    enhavo_newsletter_admin:
        resource: "@EnhavoNewsletterBundle/Resources/config/routing/admin/*"
        prefix: /admin

    enhavo_newsletter_theme:
        resource: "@EnhavoNewsletterBundle/Resources/config/routing/theme/*"
        prefix: /

11. ``Enhavo\Bundle\NewsletterBundle\Provider\ProviderInterface`` changed

.. code:: php`

    // before
    public function getTestParameters(): array;

    // after
    public function getTestReceivers(NewsletterInterface $newsletter): array;


12. Newsletter template parameters changed. The parameter ``parameters`` is now ``receiver.parameters``
